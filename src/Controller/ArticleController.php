<?php

namespace App\Controller;

use App\Entity\Message;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

use App\Service\AutoPaginationService;

use App\Repository\ArticleRepository;
use App\Repository\MessageRepository;

class ArticleController extends AbstractController
{
    #[Route('/articles', name: 'app_articles')]
    public function index(
        Request $request,
        ArticleRepository $articleRepo,
        AutoPaginationService $pageService
        ): Response
    {
        $pageInfo = $pageService->paginate(request: $request, entityRepository: $articleRepo, limit: 12);

        return $this->render('article/index.html.twig', [
            'allArticles' => $pageInfo['items'],
            'pageInfo' => $pageInfo
        ]);
    }

    #[Route('/articles/{id}', name: 'show_article')]
    public function show(
        int $id,
        ArticleRepository $articleRepo
        ): Response
    {
        $article = $articleRepo->find($id);

        return $this->render('article/show.html.twig', [
            'article' => $article,
        ]);
    }

    #[Route('/comment/{articleId}', name: 'add_comment', methods: ["POST"])]
    public function addComment(
        int $articleId,
        Request $request,
        ArticleRepository $articleRepo,
        EntityManagerInterface $entityManager
        ): Response
    {
        $user = $this->getUser();
        if (!isset($user)) { return $this->redirectToRoute("app_login"); }
        
        $article = $articleRepo->find($articleId);
        if (!isset($article)) { $this->redirectToRoute("app_home"); }


        $content = $request->get('content');

        // For some reason, an empty content is of length one ?...
        if (!isset($content) || strlen($content) <= 1) {
            return new Response(
                "Content is empty",
                Response::HTTP_BAD_REQUEST                
            );
        }
        
        
        $comment = new Message();
        $comment->setAuthor($user);
        $comment->setArticle($article);
        $comment->setContent($content);

        $entityManager->persist($comment);
        $entityManager->flush();

        return $this->redirectToRoute(route: "show_article", parameters: ["id"=> $articleId]);
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

            $entityManager->flush();
        } else {
            return $this->redirectToRoute("app_login");
        }
        return $this->redirectToRoute(route: "show_article", parameters: ["id"=> $id]);
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

            $entityManager->flush();
        } else {
            return $this->redirectToRoute("app_login");
        }
        return $this->redirectToRoute(route: "show_article", parameters: ["id"=> $id]);
    }
}
