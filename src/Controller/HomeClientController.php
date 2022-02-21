<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeClientController extends AbstractController
{
    /**
     * @Route("/home/client", name="home_client")
     */
    public function index(): Response
    {
        return $this->render('client/home_client/home.html.twig', [
            'controller_name' => 'HomeClientController',
        ]);
    }
}
