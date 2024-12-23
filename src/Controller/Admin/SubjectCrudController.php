<?php

namespace App\Controller\Admin;

use App\Entity\Subject;
use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminDashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

#[AdminDashboard(routes: [
    'index' => ['routePath' => '/all'],
    'new' => ['routePath' => '/create', 'routeName' => 'create'],
    'edit' => ['routePath' => '/editing-{entityId}', 'routeName' => 'editing'],
    'delete' => ['routePath' => '/remove/{entityId}'],
    'detail' => ['routeName' => 'view'],
])]


class SubjectCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Subject::class;
    }

    public function configureFields(string $pageName): iterable
    {
        if ($pageName === Crud::PAGE_INDEX) {
            // Champs visibles uniquement dans la page INDEX
            return [
                IdField::new('id', 'ID'),
                AssociationField::new('category', 'Categorie'),
                TextEditorField::new('title', 'Titre'),

            ];
        }

        // Champs visibles pour les autres pages (NEW, EDIT, DETAIL)
        return [
            AssociationField::new('category', 'Catégorie')
                ->setFormTypeOption('choice_label', 'title') // Assure-toi que 'name' est le champ de l'entité Category à afficher
                ->setRequired(true),
            TextField::new('title', 'Titre'),
        ];
    }
}
