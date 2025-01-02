<?php

namespace App\Controller;

use App\Entity\Subject;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
}
