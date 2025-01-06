<?php

namespace App\Controller;

use App\Entity\Image;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    private $em;
    public function __construct(EntityManagerInterface $entityManager) {
        $this->em = $entityManager;
    }

    #[Route('/', name: 'app_home')]
    public function index(ArticleRepository $articleRepository): Response
    {
        $sliderImages = $this->em->getRepository(Image::class)->findBy(['isOnSlider' => true]);
        $latestArticles = $articleRepository->findBy([], ['date' => 'DESC'], 6);

        return $this->render('home.html.twig', [
            'controller_name' => 'HomeController',
            'latestArticles' => $latestArticles,
            'sliderImages' => $sliderImages
        ]);
    }
}
