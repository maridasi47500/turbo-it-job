// src/Controller/UserController.php
namespace App\Controller;

use Doctrine\DBAL\Connection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class UserController extends AbstractController
{
    public function index(Connection $connection): Response
    {
        $users = $connection->fetchAllAssociative('SELECT * FROM users');

        // ...
    }
}
