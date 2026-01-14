<?php
// src/Controller/JobController.php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Doctrine\DBAL\Connection;

use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class JobController extends AbstractController
{
    #[Route('/', "homepage")]
    public function number(Connection $connection): Response
    {
        $number = random_int(0, 100);
        if (is_null($this->getUser()) != 1) {
        $user_id = $this->getUser()->getId();
        $email = $this->getUser()->getEmail();
        $users = $connection->fetchAllAssociative('SELECT email FROM users where email <> \'' . strval($email) . '\'  ORDER BY RANDOM() LIMIT 4;');
        $jobs = $connection->fetchAllAssociative('SELECT * FROM job where user_id = ' . strval($user_id));
        }else{
        // Create a simple array.
        $myjob = ['name' => "operation team member", 'place' => 'hey', 'company' => 'Adecco', 'dates' => 'jun 2022 - today'];
        $myuser = ['email' => 'anonymous user'];
        $jobs = array($myjob);
        $users = array($myuser);
        }
        $rayons=array(5, 10, 20, 50, 100, 150, 200);


        return $this->render('lucky/number.html.twig', [
            'rayons' => $rayons,
            'jobs' => $jobs,
            'users' => $users,
            'number' => $number
        ]);
    }
}
