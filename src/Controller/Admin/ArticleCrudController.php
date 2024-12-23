<?php

namespace App\Controller\Admin;

use App\Entity\Article;
use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminDashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Bundle\SecurityBundle\Security;
use Doctrine\ORM\EntityManagerInterface;

#[AdminDashboard(routes: [
    'index' => ['routePath' => '/all'],
    'new' => ['routePath' => '/create', 'routeName' => 'create'],
    'edit' => ['routePath' => '/editing-{entityId}', 'routeName' => 'editing'],
    'delete' => ['routePath' => '/remove/{entityId}'],
    'detail' => ['routeName' => 'view'],
])]

class ArticleCrudController extends AbstractCrudController
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }
    
    public static function getEntityFqcn(): string
    {
        return Article::class;
    }

    public function configureFields(string $pageName): iterable
    {
        if ($pageName === Crud::PAGE_INDEX) {
            // Champs visibles uniquement dans la page INDEX
            return [
                IdField::new('id', 'ID'),
                AssociationField::new('author', 'Auteur'),
                TextEditorField::new('content', 'Contenu'),
                DateTimeField::new('date', 'Date'),
            ];
        }
    
        // Champs visibles pour les autres pages (NEW, EDIT, DETAIL)
        return [
            TextField::new('title', 'Titre'),
            TextEditorField::new('content', 'Contenu'),
        ];
    }

    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if ($entityInstance instanceof Article) {
            // Définir l'auteur comme étant l'utilisateur actuellement connecté
            $user = $this->security->getUser();
            $entityInstance->setAuthor($user);

            // Définir la date comme étant la date actuelle
            $entityInstance->setDate(new \DateTime());
        }

        parent::persistEntity($entityManager, $entityInstance);
    }
}
