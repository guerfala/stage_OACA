<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\Interventions;
use App\Form\InterventionFormType;
use App\Repository\InterventionsRepository;

use Doctrine\Persistence\ManagerRegistry;

use Symfony\Component\Form\Extension\Core\Type\FileType;

use App\Controller\AgentsController;
use App\Entity\Agents;

class InterventionsController extends AbstractController
{
    #[Route('/', name: 'app_interventions')]
    public function index(): Response
    {
        return $this->render('interventions/index.html.twig', [
            'controller_name' => 'InterventionsController',
        ]);
    }

    #[Route('/{id_agent}/new', name: 'app_interventions_new', methods: ['GET', 'POST'])]
    public function new(Request $request, InterventionsRepository $InterventionsRepository, int $id_agent): Response
    {
        $interventions = new Interventions();
        $form = $this->createForm(InterventionFormType::class, $interventions);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $file = $form->get('piece_jointe')->getData();

            if ($file) {
                $fileName = $file->getPieceJointe();
    
                try {
                    $file->move(
                        $this->getParameter('uploads_directory'),
                        $fileName
                    );
                } catch (FileException $e) {
                    // Handle the exception
                }
                $interventions->setPieceJointe($fileName);
            }

            $interventions->setIdAgent($id_agent);
            $etat = "non validé";
            $interventions->setEtat($etat);

            $InterventionsRepository->save($interventions, true);

            return $this->redirectToRoute('app_interventions_front', ['id_agent' => $id_agent,], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('interventions/new.html.twig', [
            'interventions' => $interventions,
            'form' => $form,
        ]);
    }

    #[Route('/{id_agent}/front', name: 'app_interventions_front', methods: ['GET'])]
    public function front(InterventionsRepository $InterventionsRepository, int $id_agent): Response
    {
        $userRepository = $this->getDoctrine()->getRepository(Agents::class);
        $interventions = $InterventionsRepository->findBy(['id_agent'=>$id_agent]);
   
        return $this->render('interventions/front.html.twig', [
            'id_agent' => $id_agent,
            'interventions' => $interventions,
        ]);
    }
}
