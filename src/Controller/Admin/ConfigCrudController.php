<?php

namespace App\Controller\Admin;

use App\Entity\Config;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ConfigCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Config::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('LogoText', 'Nom du site'),
            TextField::new('block1Title', 'Titre du bloc 1'),
            TextEditorField::new('block1Text', 'Contenu du texte du bloc 1'),
            TextField::new('block2Title', 'Titre du bloc 2'),
            TextEditorField::new('block2Text', 'Contenu du texte du bloc 2'),
            TextField::new('block3Title', 'Titre du bloc 3'),
            TextEditorField::new('block3Text', 'Contenu du texte du bloc 3'),
            TextEditorField::new('footerText', 'Texte du footer'),
            TextField::new('footerLink1', 'Lien 1 du footer'),
            TextField::new('footerLink1Label', 'Texte associé lien 1'),
            TextField::new('footerLink2', 'Lien 2 du footer'),
            TextField::new('footerLink2Label', 'Texte associé lien 2'),
            TextField::new('footerLink3', 'Lien 3 du footer'),
            TextField::new('footerLink3Label', 'Texte associé lien 3'),
        ];
    }
}
