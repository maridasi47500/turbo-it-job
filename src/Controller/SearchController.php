<?php
// src/Controller/SearchController.php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Doctrine\DBAL\Connection;
use Symfony\Component\HttpFoundation\Request;


use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Psr\Log\LoggerInterface;
use Symfony\Component\Cache\CacheItem;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;



class SearchController extends AbstractController
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var HttpClientInterface
     */
    private $client;

    /**
     * @var CacheInterface
     */
    private $cache;

    /**
     * LocalisationController constructor.
     *
     * @param LoggerInterface $logger
     * @param CacheInterface $cache
     * @param HttpClientInterface $client
     */
    public function __construct(LoggerInterface $logger, CacheInterface $cache, HttpClientInterface $client)
    {
        $this->logger = $logger;
        $this->cache = $cache;
        $this->client = $client;
    }

    #[Route('/chercheroffredemploi', "jobsearch")]
    public function number(Request $request, Connection $connection): Response
    {

        $number = random_int(0, 100);
        $mysearch = $request->get('job');
        $address = $request->get('place');
        $rayon = $request->get('rayon');
        $search = "%" . str_replace(" ", "%", strtolower($mysearch)) . "%";
        $rayons=array(5, 10, 20, 50, 100, 150, 200);

        /** @var CacheItem $item */
        $item = $this->cache->getItem($address);

        // On vérifie si l'item cache est toujours valable
        if (strlen($address) > 0 && !$item->isHit()) {
         $url = sprintf('http://nominatim.openstreetmap.org/search?q=%s&format=%s&polygon=%s&addressdetails=%s', $address, 'json', '1', '1');
         $response = $this->client->request('GET', $url);
         $this->logger->info('User localisation', ['provider' => 'ip', 'url' => $url, 'response' => $response]);

         //On stocke la valeur et on ajoute une date d'expiration
         $item
             ->set($response->toArray())
             ->expiresAfter(3600)
         ;
         $this->cache->save($item);
        }

        $listplaces=$item->get();




        if (is_string($listplaces) ){

        $jobs = $connection->fetchAllAssociative('SELECT name, lat, lon, description, place, company, dates FROM job_offer where lower(name) like \'' . $search . '\' or lower(company) like \'' . $search . '\' or lower(place) like \'' . $search . '\' or lower(description) like \'' . $search . '\'');
        }else{

        $firstplace=$listplaces[0];
        //echo $firstplace["display_name"];
        $lat=$firstplace["lat"];
        $lon=$firstplace["lon"];
        $jobs = $connection->fetchAllAssociative('SELECT name,( 3959 * acos( cos( radians(cast(' . $lat . ' as float)) ) * cos( radians(cast(lat as float)) ) * cos( radians(cast(lon as float)) - radians(cast(' . $lon . ' as float)) ) + sin( radians(cast(' . $lat . ' as float)) ) * sin( radians(cast(lat as float)) ) ) ) AS distance, lat, lon, description, place, company, dates FROM job_offer where (lower(name) like \'' . $search . '\' or lower(company) like \'' . $search . '\' or lower(place) like \'' . $search . '\' or lower(description) like \'' . $search . '\') and ( 3959 * acos( cos( radians(cast(' . $lat . ' as float)) ) * cos( radians(cast(lat as float)) ) * cos( radians(cast(lon as float)) - radians(cast(' . $lon . ' as float)) ) + sin( radians(cast(' . $lat . ' as float)) ) * sin( radians(cast(lat as float)) ) ) ) < ' . $rayon);
        //$jobs = $connection->fetchAllAssociative('SELECT name, lat, lon, description, place, company, dates FROM job_offer where lower(name) like \'' . $search . '\' or lower(company) like \'' . $search . '\' or lower(place) like \'' . $search . '\' or lower(description) like \'' . $search . '\'');
        }


        return $this->render('job/search.html.twig', [
            'mysearch' => $mysearch,
            'jobs' => $jobs,
            'address' => $address,
            'rayon' => intval($rayon),
            'rayons' => $rayons,
            'number' => $number
        ]);
    }
}
