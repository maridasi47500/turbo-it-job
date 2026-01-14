<?php
// src/Controller/NewJobController.php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\SecurityBundle\Security;

use Symfony\Component\HttpFoundation\Request;
//use Doctrine\ORM\EntityManagerInterface;

use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Job;
use Doctrine\ORM\EntityManagerInterface;




// ...

class NewJobController extends AbstractController
{
    #[Route('/createjob')]
    public function job(Request $request, Security $security, EntityManagerInterface $entityManager): Response
    {
        //echo $request;
        $number = random_int(0, 100);
        if ($request->getMethod() == "POST") {    // GET, POST, PUT, DELETE, HEAD
            $job = new Job();
            $job->setName($request->getPayload()->get('nom'));
            $job->setCompany($request->getPayload()->get('company'));
            $job->setUserId($request->getPayload()->get('user_id'));
            $job->setPlace($request->getPayload()->get('lieu'));
            $job->setDates($request->getPayload()->get('dates'));

            $entityManager->persist($job);
            $entityManager->flush();
            return $this->redirectToRoute('homepage');


        }

        return $this->render('job/form.html.twig', [
            'number' => $number
        ]);
    }
}
