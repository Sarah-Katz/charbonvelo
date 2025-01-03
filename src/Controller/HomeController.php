<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(ArticleRepository $articleRepository): Response
    {
        $latestArticles = $articleRepository->findBy([], ['date' => 'DESC'], 6);

        return $this->render('home.html.twig', [
            'controller_name' => 'HomeController',
            'latestArticles' => $latestArticles,
        ]);
    }
}
