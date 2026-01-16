<?php

//declare(strict_types=1);

namespace App\Controller;

//use App\Service\Localisation\LocalisationInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\Routing\Attribute\Route;
use Psr\Log\LoggerInterface;
use Symfony\Component\Cache\CacheItem;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * Class LocalisationController.
 */
class LocalisationController extends AbstractController
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

  /**
   * @Route("/localisation", name="localisation", options={"expose"=true})
   * @param Request $request
   *
   * @return JsonResponse
   */
  #[Route('/localisation', "malocalisation")]
  public function getCurrentLocalisation(Request $request): JsonResponse
  {
     $address = $request->query->get('address');

     /** @var CacheItem $item */
     $item = $this->cache->getItem($address);

     // On vérifie si l'item cache est toujours valable
     if (!$item->isHit()) {
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

     return new JsonResponse($item->get());
  }
}
