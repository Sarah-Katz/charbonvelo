<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

use App\Repository\ArticleRepository;

class ArticleController extends AbstractController
{
    #[Route('/article/{id}', name: 'app_article')]
    public function index(
        int $id,
        ArticleRepository $articleRepo
        ): Response
    {
        $article = $articleRepo->find($id);

        return $this->render('article/index.html.twig', [
            'article' => $article,
        ]);
    }
}
