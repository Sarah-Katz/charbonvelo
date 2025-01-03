<?php

namespace App\Controller\Admin;

use App\Entity\Article;
use App\Entity\Message;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminDashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Bundle\SecurityBundle\Security;

#[AdminDashboard(routes: [
    'index' => ['routePath' => '/all'],
    'new' => ['routePath' => '/create', 'routeName' => 'create'],
    'edit' => ['routePath' => '/editing-{entityId}', 'routeName' => 'editing'],
    'delete' => ['routePath' => '/remove/{entityId}'],
    'detail' => ['routeName' => 'view'],
])]

class MessageCrudController extends AbstractCrudController
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public static function getEntityFqcn(): string
    {
        return Message::class;
    }

    public function configureFields(string $pageName): iterable
    {
        if ($pageName === Crud::PAGE_INDEX) {
            // Champs visibles uniquement dans la page INDEX
            return [
                IdField::new('id', 'ID'),
                AssociationField::new('author', 'Auteur'),
                AssociationField::new('subject', 'Sujet'),
                AssociationField::new('article', 'Article'),
                TextField::new('content', 'Contenu'),
                DateTimeField::new('date', 'Date'),
            ];
        }

        // Champs visibles pour les autres pages (NEW, EDIT, DETAIL)
        return [
            AssociationField::new('subject', 'Sujet')
                ->setFormTypeOption('choice_label', 'title')
                ->setRequired(false),
            AssociationField::new('article', 'Article')
                ->setFormTypeOption('choice_label', 'title') // Assure-toi que 'name' est le champ de l'entité Category à afficher
                ->setRequired(false),
            TextField::new('content', 'Contenu'),
        ];
    }

    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if ($entityInstance instanceof Message) {
            // Définir l'auteur comme étant l'utilisateur actuellement connecté
            $user = $this->security->getUser();
            $entityInstance->setAuthor($user);

            // Définir la date comme étant la date actuelle
            $entityInstance->setDate(new \DateTime());
        }

        parent::persistEntity($entityManager, $entityInstance);
    }
}
