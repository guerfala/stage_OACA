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

class InterventionsController extends AbstractController
{
    #[Route('/', name: 'app_interventions')]
    public function index(): Response
    {
        return $this->render('interventions/index.html.twig', [
            'controller_name' => 'InterventionsController',
        ]);
    }

    #[Route('/new', name: 'app_interventions_new', methods: ['GET', 'POST'])]
    public function new(Request $request, InterventionsRepository $InterventionsRepository): Response
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

            $InterventionsRepository->save($interventions, true);

            return $this->redirectToRoute('app_interventions', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('interventions/new.html.twig', [
            'interventions' => $interventions,
            'form' => $form,
        ]);
    }
}
