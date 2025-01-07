<?php

namespace App\Controller;

use App\Entity\Message;
use App\Form\MessageType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

// TODO: Make users also be able to edit and delete their own messages
#[Route('/moderator')]
#[IsGranted("ROLE_MODERATOR")]
class ModeratorController extends AbstractController
{
    private $em;
    public function __construct(EntityManagerInterface $em) {
        $this->em = $em;
    }
    
    #[Route('/message/edit/{id}', name: 'mod_edit_message')]
    public function edit_message(Message $message, Request $request): Response
    {
        // We fetch the form that we're either using, or need to use
        $form = $this->createForm(MessageType::class, $message);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // We save the message
            $this->em->flush();
            $this->addFlash("success",
                "Message #".$message->getId()." a été modifié par ".$this->getUser()->getUsername()."#".$this->getUser()->getId()." avec succès"
            );

            return $this->redirectMessage($message);
        }

        return $this->render('moderator/edit_message.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    
    #[Route('/message/delete/{id}', name: 'mod_delete_message')]
    public function deleteMessage(Message $message) : Response
    {
        $this->em->remove($message);
        $this->em->flush();

        $this->addFlash("success",
            "Message #".$message->getId()." a été suprimmé par ".$this->getUser()->getUsername()."#".$this->getUser()->getId()." avec succès"
        );
        return $this->redirectMessage($message);
    }

    // Utility Functions
    // We need to check where to redirect the moderator
    public function redirectMessage(Message $message) : Response
    {
        // If it has a subject, redirect to the subject in question
        $subject = $message->getSubject();
        if (!empty($subject)) {
            return $this->redirectToRoute("app_subject_show", [
                "id"=> $subject->getId()
            ]);
        }
        
        // Otherwise, redirect to the article
        $article = $message->getArticle();
        if (!empty($article)) {
            return $this->redirectToRoute("show_article", [
                "id"=> $article->getId()
            ]);
        }

        // If it has none, redirect to the homepage, still, something went wrong
        return $this->redirectToRoute("app_home");
    }
}
