<?php
// src/Controller/NewJobController.php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Job;
use Doctrine\ORM\EntityManagerInterface;




// ...

class NewJobController extends AbstractController
{
    #[Route('/createjob')]
    public function job(Request $request, EntityManagerInterface $entityManager): Response
    {
        echo $request;
        $number = random_int(0, 100);
        if ($request->getMethod() == "POST") {;    // GET, POST, PUT, DELETE, HEAD
            $job = new Job();
            $job->setName($request->query->get('name'))

            $entityManager->persist($user);
            $entityManager->flush();


        }

        return $this->render('job/form.html.twig', [
            'number' => $number
        ]);
    }
}
