<?php

namespace App\Controller\Admin;

use App\Entity\Image;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use Vich\UploaderBundle\Form\Type\VichImageType;

class ImageCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Image::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->onlyOnIndex(),
            
            // Champ pour télécharger une nouvelle image
            FormField::addPanel('Image')->onlyOnForms(),
            TextField::new('imageFile', 'Télécharger une image')
                ->setFormType(VichImageType::class)
                ->setFormTypeOptions([
                    'allow_delete' => true, // Permettre la suppression de l'image
                    'delete_label' => 'Supprimer l\'image existante',
                    'download_uri' => false, // Désactiver le lien de téléchargement
                ])
                ->onlyOnForms(),


            TextField::new('alt', 'Description alternative')
                ->setHelp('Texte alternatif pour l\'accessibilité (facultatif)')
                ->setRequired(false),

            BooleanField::new('isOnSlider', 'Afficher sur le slider'),

            AssociationField::new('articles', 'Articles associés')
                ->onlyOnIndex(),
        ];
    }

    public function createEntity(string $entityFqcn): Image
    {
        $image = new Image();
        $image->setIsOnSlider(false);
        return $image;
    }
}
