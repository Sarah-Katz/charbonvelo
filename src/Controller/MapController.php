<?php

namespace App\Controller;

use App\Repository\StageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MapController extends AbstractController
{
    private StageRepository $stageRepository;

    // Injecter le repository Stage dans le constructeur
    public function __construct(StageRepository $stageRepository)
    {
        $this->stageRepository = $stageRepository;
    }

    #[Route('/map', name: 'app_map')]
    public function index(): Response
    {
        // Récupérer toutes les entités Stage
        $stages = $this->stageRepository->findAll();

        return $this->render('map/index.html.twig', [
            'controller_name' => 'MapController',
            'stages' => $stages, // Passer les stages à Twig
        ]);
    }
}
