<?php

namespace App\Controller;

use App\Entity\Message;
use App\Entity\Subject;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class SubjectController extends AbstractController
{

    public function __construct(private EntityManagerInterface $em)
    {
    }

    #[Route('/subject/{id}', name: 'app_subject_show')]
    public function showSubject(int $id): Response
    {
        $subject = $this->em->getRepository(Subject::class)->find($id);

        return $this->render('subject/show.html.twig', [
            'controller_name' => 'SubjectController',
            'subject'         => $subject,
        ]);
    }

    #[Route('/subject/{id}/newMessage', name: 'app_message_new', methods: ['POST'])]
    public function newMessage(int $id, Request $request): Response
    {
        $subject = $this->em->getRepository(Subject::class)->find($id);
        $message = new Message();
        $form    = $this->createForm(MessageFormType::class, $message);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $message->setSubject($subject);
            $this->em->persist($message);
            $this->em->flush();

            return $this->redirectToRoute('app_subject_show', ['id' => $id]);
        }

        return $this->render('subject/newMessage.html.twig', [
            'controller_name' => 'SubjectController',
            'form'            => $form->createView(),
            'subject'         => $subject,
        ]);
    }

    #[Route('/like/comment/{id}/{commentId}', name: 'like_article_comment', methods: ["POST"])]
    public function likeComment(int $id, int $commentId): Response
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
        return $this->redirectToRoute(route: "app_article", parameters: ["id" => $id]);
    }
}
