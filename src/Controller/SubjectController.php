<?php

namespace App\Controller;

use App\Entity\Category;
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

    // Subject methods

    #[Route('/subject/{id}', name: 'app_subject_show')]
    public function showSubject(int $id): Response
    {
        $subject = $this->em->getRepository(Subject::class)->find($id);

        return $this->render('subject/show.html.twig', [
            'controller_name' => 'SubjectController',
            'subject'         => $subject,
        ]);
    }

    #[Route('/subject/{id}/new', name: 'app_subject_new', methods: ['GET', 'POST'])]
    public function newSubject(int $id, Request $request): Response
    {
        if ($request->isMethod('POST')) {
            $subject = new Subject();
            $message = new Message();

            $subject->setTitle($request->request->get('title'));
            $subject->setCategory($this->em->getRepository(Category::class)->find($id));
            $this->em->persist($subject);


            $message->setAuthor($this->getUser());
            $message->setContent($request->request->get('content'));
            $message->setSubject($subject);
            $this->em->persist($message);

            $this->em->flush();

            return $this->redirectToRoute('app_subject_show', ['id' => $subject->getId()]);
        }

        return $this->render('subject/new.html.twig', [
            'controller_name' => 'SubjectController',
            'id'              => $id,
        ]);
    }


    // Messages Methods

    #[Route('/subject/{id}/newMessage', name: 'app_message_new', methods: ['POST'])]
    public function newMessage(int $id, Request $request): Response
    {
        $subject = $this->em->getRepository(Subject::class)->find($id);

        $message = new Message();
        $message->setSubject($subject);
        $message->setContent($request->request->get('content'));
        $message->setAuthor($this->getUser());

        $this->em->persist($message);
        $this->em->flush();

        return $this->redirectToRoute('app_subject_show', ['id' => $id]);
    }

    #[Route('/like/message/{id}', name: 'like_subject_message', methods: ["POST"])]
    public function likeMessage(int $id): Response
    {
        $user    = $this->getUser();
        $message = $this->em->getRepository(Message::class)->find($id);
        if ($user->getLikedMessages()->contains($message)) {
            $user->removeLikedMessage($message);
        } else {
            $user->addLikedMessage($message);
        }

        $this->em->flush();

        $idSubject = $message->getSubject()->getId();

        return $this->redirectToRoute(route: "app_subject_show", parameters: ["id" => $idSubject]);
    }
}
