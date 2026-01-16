<?php
// src/Controller/JobOfferController.php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\SecurityBundle\Security;

use Symfony\Component\HttpFoundation\Request;
//use Doctrine\ORM\EntityManagerInterface;

use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\JobOffer;
use Doctrine\ORM\EntityManagerInterface;




// ...

class JobOfferController extends AbstractController
{
    #[Route('/createjoboffer')]
    public function job(Request $request, Security $security, EntityManagerInterface $entityManager): Response
    {
        //echo $request;
        $number = random_int(0, 100);
        if ($request->getMethod() == "POST") {    // GET, POST, PUT, DELETE, HEAD
            $job = new JobOffer();
            $job->setName($request->getPayload()->get('nom'));
            $job->setCompany($request->getPayload()->get('company'));
            $job->setPlace($request->getPayload()->get('lieu'));
            $job->setDates($request->getPayload()->get('dates'));
            $job->setLat($request->getPayload()->get('lat'));
            $job->setLon($request->getPayload()->get('lon'));
            $job->setDescription($request->getPayload()->get('description'));

            $entityManager->persist($job);
            $entityManager->flush();
            return $this->redirectToRoute('homepage');


        }

        return $this->render('joboffer/form.html.twig', [
            'number' => $number
        ]);
    }
}
