<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class InterventionsController extends AbstractController
{
    #[Route('/interventions', name: 'app_interventions')]
    public function index(): Response
    {
        return $this->render('interventions/index.html.twig', [
            'controller_name' => 'InterventionsController',
        ]);
    }
}
