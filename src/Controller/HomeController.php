<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app.home', methods: ['GET'])]
    public function home(): Response
    {
        $firstName = "Pierre";
        $lastName = "Bertrand";

        return $this->render('/home/index.html.twig', [
            'firstName' => $firstName,
            'lastName' => $lastName
        ]);
    }
}