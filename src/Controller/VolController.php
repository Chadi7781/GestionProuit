<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class VolController extends AbstractController
{
    /**
     * @Route("/vol", name="vol")
     */
    public function index(): Response
    {
        return $this->render('vol/vol.html.twig', [
            'controller_name' => 'VolController',
        ]);
    }

    /**
     * @Route("/create-vol", name="create")
     */
    public function create(): Response
    {
        return $this->render('vol/create.html.twig', [
            'controller_name' => 'VolController',
        ]);
    }
}
