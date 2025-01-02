<?php

namespace App\Controller;

use App\Entity\Category;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ForumController extends AbstractController
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    #[Route('/forum', name: 'app_forum')]
    public function index(): Response
    {
        $categories = $this->entityManager->getRepository(Category::class)->findAll();
        return $this->render('forum/index.html.twig', [
            'controller_name' => 'ForumController',
            'categories' => $categories,
        ]);
    }

    #[Route('/forum/{category}', name: 'app_category_show')]
    public function showCategory(Category $category): Response
    {
        return $this->render('forum/category.html.twig', [
            'controller_name' => 'ForumController',
            'category' => $category,
        ]);
    }
}
