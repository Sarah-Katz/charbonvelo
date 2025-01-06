<?php

namespace App\Controller\Admin;

use App\Entity\Article;
use App\Entity\Category;
use App\Entity\Image;
use App\Entity\Message;
use App\Entity\Stage;
use App\Entity\Subject;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Vich\UploaderBundle\Form\Type\VichFileType;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\RouterInterface;

class DashboardController extends AbstractDashboardController
{
    private $router;

    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    #[Route('/QW50b2luZUFWZWxv', name: 'admin')]
    public function index(): Response
    {
        return $this->render('admin/dashboard.html.twig', [
            'some_variable' => 'Some value']);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Charbonvelo');
    }

    public function configureMenuItems(): iterable
    {        
        yield MenuItem::linkToUrl('Retour à l\'accueil', 'fa fa-arrow-left', $this->router->generate('app_home'));
        
        // Ajout d'une ligne de séparation
        yield MenuItem::section();
        
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToCrud('Article', 'fas fa-newspaper', Article::class);
        yield MenuItem::linkToCrud('Category', 'fas fa-bars', Category::class);
        yield MenuItem::linkToCrud('Message', 'fas fa-message', Message::class);
        yield MenuItem::linkToCrud('Stage', 'fas fa-road', Stage::class);
        yield MenuItem::linkToCrud('Subject', 'fas fa-envelope', Subject::class);
        yield MenuItem::linkToCrud('Users', 'fas fa-users', User::class);
        yield MenuItem::linkToCrud('Image', 'fas fa-image', Image::class);
    }    
}