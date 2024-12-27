<?php

namespace App\Controller\Admin;

use App\Entity\Stage;
use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminDashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Finder\Finder;
use Vich\UploaderBundle\Form\Type\VichFileType;

#[AdminDashboard(routes: [
    'index' => ['routePath' => '/all'],
    'new' => ['routePath' => '/create', 'routeName' => 'create'],
    'edit' => ['routePath' => '/editing-{entityId}', 'routeName' => 'editing'],
    'delete' => ['routePath' => '/remove/{entityId}'],
    'detail' => ['routeName' => 'view'],
])]

class StageCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Stage::class;
    }

    /*
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('title'),
            TextEditorField::new('description'),
        ];
    }
    */

    

    public function configureFields(string $pageName): iterable
    {
        if ($pageName === Crud::PAGE_INDEX) {
            // Affiche uniquement les informations nécessaires dans l'index
            return [
                IdField::new('id', 'ID'),
                TextField::new('title', 'Titre'),
                TextEditorField::new('description', 'Description'),
                TextField::new('gpxLink', 'Lien GPX') // Affiche uniquement le chemin du fichier
            ];
        }
    
        // Récupère les fichiers GPX déjà uploadés
        $gpxFiles = $this->getUploadedGpxFiles();
    
        // Champs visibles pour les pages NEW et EDIT
        return [
            TextField::new('title', 'Titre'),
            TextEditorField::new('description', 'Description'),
            Field::new('gpxFile')
                ->setFormType(VichFileType::class)
                ->onlyOnForms(), // Gère l'upload
            ChoiceField::new('gpxLink', 'Fichiers GPX disponibles')
                ->setChoices(array_combine($gpxFiles, $gpxFiles)) // Remplit la liste avec les fichiers
                ->allowMultipleChoices(false)
                ->renderExpanded(false) // Menu déroulant classique
                ->setRequired(false) // Optionnel
                ->onlyWhenCreating(), // Visible uniquement lors de la création
            TextField::new('gpxLink', 'Lien GPX')->onlyOnDetail(), // Affiche le chemin en détail
        ];
    }
    
    /**
     * Récupère les fichiers GPX déjà uploadés.
     *
     * @return array Liste des fichiers disponibles
     */
    private function getUploadedGpxFiles(): array
    {
        $gpxDirectory = $this->getParameter('kernel.project_dir') . '\public\uploads\gpx';
        $files = [];
    
        if (is_dir($gpxDirectory)) {
            foreach (scandir($gpxDirectory) as $file) {
                if (pathinfo($file, PATHINFO_EXTENSION) === 'xml') {
                    $files[] = $file; // Ajoute uniquement les fichiers GPX
                }
            }
        }
    
        dump($files); // Vérifiez la liste obtenue
        return $files;
    }
    

    

}
