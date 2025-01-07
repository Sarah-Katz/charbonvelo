<?php

namespace App\Controller;

use App\Entity\Message;
use App\Entity\Article;
use App\Entity\Image;

use Doctrine\ORM\EntityManagerInterface;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\String\Slugger\SluggerInterface;

use App\Service\AutoPaginationService;

class ArticleController extends AbstractController
{
    private $em;
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
    }

    #[Route('/articles', name: 'app_articles')]
    public function index(
        Request $request,
        AutoPaginationService $pageService
    ): Response {
        $queryBuilder = $this->em->getRepository(Article::class)->createQueryBuilder('a');
        $queryBuilder->orderBy("a.date", "DESC");

        $pageInfo = $pageService->paginate(request: $request, queryBuilder: $queryBuilder, limit: 12);

        return $this->render('article/index.html.twig', [
            'allArticles' => $pageInfo['items'],
            'pageInfo'    => $pageInfo
        ]);
    }

    #[Route('/articles/{id}', name: 'show_article')]
    public function show(
        int $id
    ): Response {
        $article = $this->em->getRepository(Article::class)->find($id);

        return $this->render('article/show.html.twig', [
            'article' => $article,
        ]);
    }

    #[Route('/article/new', name: 'add_article', methods: ["GET", "POST"])]
    #[Route('/article/new', name: 'add_article', methods: ["GET", "POST"])]
    public function addArticle(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        if ($request->isMethod("POST")) {
            $article = new Article();
            $article->setTitle($request->get('title'));
            $article->setContent($request->get('content'));
            $article->setAuthor($this->getUser());

            $imageFile = $request->files->get('image');
            if ($imageFile) {
                // Check file type
                $mimeType     = $imageFile->getMimeType();
                $allowedTypes = ['image/jpeg', 'image/png'];
                if (!in_array($mimeType, $allowedTypes)) {
                    $this->addFlash('error', 'Image en .png et .jpg seulement.');
                    return $this->redirectToRoute('add_article');
                }

                // Check file size (2MB = 2 * 1024 * 1024 bytes)
                if ($imageFile->getSize() > 2 * 1024 * 1024) {
                    $this->addFlash('error', "L'image doit peser moins de 2MB.");
                    return $this->redirectToRoute('add_article');
                }
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename     = $slugger->slug($originalFilename);
                $newFilename      = $safeFilename . '-' . uniqid() . '.' . $imageFile->guessExtension();

                try {
                    $imageFile->move(
                        $this->getParameter('images_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    $this->addFlash('error', "Une erreur s'est produite lors de l'envoie de l'image.");
                    return $this->redirectToRoute('add_article');
                }

                $image = new Image();
                $image->setPath($newFilename);
                $image->setAlt($article->getTitle());
                $image->setIsOnSlider(false);
                $entityManager->persist($image);

                $article->setImage($image);
            }

            $entityManager->persist($article);
            $entityManager->flush();

            $this->addFlash('success', 'Article créé avec succès.');
            return $this->redirectToRoute("app_articles");
        }

        return $this->render('article/new.html.twig');
    }

    #[Route('/comment/{articleId}', name: 'add_comment', methods: ["POST"])]
    public function addComment(
        int $articleId,
        Request $request,
        EntityManagerInterface $entityManager
    ): Response {
        $user = $this->getUser();
        if (!isset($user)) {
            return $this->redirectToRoute("app_login");
        }

        $article = $this->em->getRepository(Article::class)->find($articleId);
        if (!isset($article)) {
            $this->redirectToRoute("app_home");
        }


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

        return $this->redirectToRoute(route: "show_article", parameters: ["id" => $articleId]);
    }

    #[Route('/like/article/{id}', name: 'like_article', methods: ["POST"])]
    public function likeArticle(
        int $id,
        EntityManagerInterface $entityManager
    ): Response {
        $user = $this->getUser();

        if (isset($user)) {
            $article = $this->em->getRepository(Article::class)->find($id);

            if ($user->getLikedArticle()->contains($article)) {
                $user->removeLikedArticle($article);
            } else {
                $user->addLikedArticle($article);
            }

            $entityManager->flush();
        } else {
            return $this->redirectToRoute("app_login");
        }
        return $this->redirectToRoute(route: "show_article", parameters: ["id" => $id]);
    }

    #[Route('/like/comment/{id}/{commentId}', name: 'like_article_comment', methods: ["POST"])]
    public function likeComment(
        int $id,
        int $commentId
    ): Response {
        $user = $this->getUser();

        if (isset($user)) {
            $comment = $this->em->getRepository(Message::class)->find($commentId);

            if ($user->getLikedMessages()->contains($comment)) {
                $user->removeLikedMessage($comment);
            } else {
                $user->addLikedMessage($comment);
            }

            $this->em->flush();
        } else {
            return $this->redirectToRoute("app_login");
        }
        return $this->redirectToRoute(route: "show_article", parameters: ["id" => $id]);
    }
}
