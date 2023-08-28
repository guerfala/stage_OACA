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

use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelHigh;
use Endroid\QrCode\Label\Alignment\LabelAlignmentCenter;
use Endroid\QrCode\Label\Font\NotoSans;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\RoundBlockSizeMode\RoundBlockSizeModeMargin;
use Endroid\QrCode\Writer\PngWriter;

class InterventionsController extends AbstractController
{
    #[Route('/index', name: 'app_interventions_index', methods: ['GET'])]
    public function index(InterventionsRepository $InterventionsRepository): Response
    {
        return $this->render('interventions/index.html.twig', [
            'interventions' => $InterventionsRepository->findAll(),
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
                $fileName = $file->getClientOriginalName();
    
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
            'id_agent' => $id_agent,
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

    #[Route('/{id}/delete', name: 'app_interventions_delete', methods: ['POST'])]
    public function delete(Request $request, InterventionsRepository $InterventionsRepository, int $id): Response
    {
        $intervention = $InterventionsRepository->findOneBy(['id'=>$id]);
        $InterventionsRepository->remove($intervention, true);

        return $this->redirectToRoute('app_interventions_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/traite', name: 'app_interventions_traite', methods: ['GET', 'POST'])]
    public function traite(Request $request, InterventionsRepository $InterventionsRepository, int $id): Response
    {
        $interventions = $InterventionsRepository->findOneBy(['id'=>$id]);
        $interventions->setEtat("en cours de traitement");
        $InterventionsRepository->save($interventions, true);

        return $this->redirectToRoute('app_interventions_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/search', name: 'app_interventions_search')]
    public function search(Request $request, InterventionsRepository $InterventionsRepository): Response
    {
        $value = $request->request->get('value');
        $interventions = $InterventionsRepository->searchBynom($value);
        return $this->render('interventions/search.html.twig', [
            'interventions' => $interventions
        ]);
    }

    #[Route('/{id}/intervention', name: 'app_interventions_show', methods: ['GET'])]
    public function show(InterventionsRepository $repository, int $id): Response
    {
        $intervention = $repository->findOneBy(['id'=>$id]);
        $InterventionInfo = "Id: " . $intervention->getId() . "\n" .
            "Au service : " . $intervention->getAuService() . "\n" .
            "Service demandeur : " . $intervention->getServiceDemandeur() . "\n" .
            "Reference : " . $intervention->getReference() . "\n" .
            "Etat : " . $intervention->getEtat() . "\n";

        // Generate QR code with car information
        $qrCode = Builder::create()
            ->writer(new PngWriter())
            ->writerOptions([])
            ->data($InterventionInfo)
            ->encoding(new Encoding('UTF-8'))
            ->errorCorrectionLevel(new ErrorCorrectionLevelHigh())
            ->size(300)
            ->margin(10)
            ->roundBlockSizeMode(new RoundBlockSizeModeMargin())
            ->labelText("")
            ->labelFont(new NotoSans(20))
            ->labelAlignment(new LabelAlignmentCenter())
            ->build();

        return $this->render('interventions/show.html.twig', [
            'intervention' => $intervention,
            'qr' => $qrCode->getDataUri(),
        ]);
    }
/*
    #[Route('/stat', name: 'app_reclamation_stat', methods: ['POST','GET'])]
    public function stat( ReclamationRepository $repo): Response
    {
        $total = $repo->countByLibelle('Technique') +
            $repo->countByLibelle('Eco') +
            $repo->countByLibelle('Other');

        $TechniqueCount = $repo->countByLibelle('Technique');
        $EcoCount = $repo->countByLibelle('Eco');
        $OtherCount = $repo->countByLibelle('Other');


        $TechniquePercentage = round(($TechniqueCount / $total) * 100);
        $EcoPercentage = round(($EcoCount / $total) * 100);
        $OtherPercentage = round(($OtherCount / $total) * 100);
        return $this->render('reclamation/stat.html.twig', [
            'TechniquePercentage' => $TechniquePercentage,
            'EcoPercentage' => $EcoPercentage,
            'OtherPercentage' => $OtherPercentage,

        ]);
    }

    #[Route('/exportpdf', name: 'exportpdf_rec')]
    public function exportToPdf(ReclamationRepository $repository): Response
    {
        // Récupérer les données de réservation depuis votre base de données
        $reclamations = $repository->findAll();

        // Créer le tableau de données pour le PDF
        $tableData = [];
        foreach ($reclamations as $reclamation) {
            $tableData[] = [
                'type' => $reclamation->getType(),
                'commentaire' => $reclamation->getCommentaire(),
                'etat' => $reclamation->getEtat(),
                //'price' => $reservation->getIdVoiture()->getPrixJours(), jointure
                'date_creation' => $reclamation->getDateCreation()->format('Y-m-d H:i:s'),
            ];
        }

        // Créer le PDF avec Dompdf
        $dompdf = new Dompdf();
        $html = $this->renderView('reclamation/export-pdf-rec.html.twig', [
            'tableData' => $tableData,
        ]);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();

        // Envoyer le PDF au navigateur
        $response = new Response($dompdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="reclamation.pdf"',
        ]);
        return $response;
    }
*/
}
