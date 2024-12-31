<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

use App\Repository\ArticleRepository;
use App\Repository\MessageRepository;

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

    #[Route('/like/article/{id}', name: 'like_article', methods: ["POST"])]
    public function likeArticle(
        int $id,
        ArticleRepository $articleRepo,
        EntityManagerInterface $entityManager
        ): Response
    {
        $user = $this->getUser();

        if (isset($user)) {
            $article = $articleRepo->find($id);

            if ($user->getLikedArticle()->contains($article)) {
                $user->removeLikedArticle($article);
            } else {
                $user->addLikedArticle($article);
            }

            $entityManager->persist($user);
            $entityManager->persist($article);

            // actually executes the queries (i.e. the INSERT query)
            $entityManager->flush();
        } else {
            // TODO: Do something special if the user isn't logged in
        }
        return $this->redirectToRoute(route: "app_article", parameters: ["id"=> $id]);
    }

    #[Route('/like/comment/{id}/{commentId}', name: 'like_article_comment', methods: ["POST"])]
    public function likeComment(
        int $id,
        int $commentId,
        MessageRepository $messageRepo,
        EntityManagerInterface $entityManager
        ): Response
    {
        $user = $this->getUser();

        if (isset($user)) {
            $comment = $messageRepo->find($commentId);

            if ($user->getLikedMessages()->contains($comment)) {
                $user->removeLikedMessage($comment);
            } else {
                $user->addLikedMessage($comment);
            }

            // actually executes the queries (i.e. the INSERT query)
            $entityManager->flush();
        } else {
            // TODO: Do something special if the user isn't logged in
        }
        return $this->redirectToRoute(route: "app_article", parameters: ["id"=> $id]);
    }
}
