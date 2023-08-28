<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Agents;
use App\Entity\Interventions;
use App\Form\InterventionFormType;
use App\Repository\InterventionsRepository;

class AgentsController extends AbstractController
{
    #[Route('/agents', name: 'app_agents')]
    public function index(): Response
    {
        return $this->render('agents/index.html.twig', [
            'controller_name' => 'AgentsController',
        ]);
    }

    #[Route('/{id_agent}/profilag', name: 'profilag')]
    public function profil(int $id_agent): Response
    {
        return $this->render('agents/profil.html.twig', [
            'id_agent' => $id_agent,
            'controller_name' => 'AgentsController',
        ]);
    }


}
