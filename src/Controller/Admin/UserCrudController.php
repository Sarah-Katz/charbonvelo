<?php

namespace App\Controller\Admin;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserCrudController extends AbstractCrudController
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureFields(string $pageName): iterable
    {
        if ($pageName === Crud::PAGE_INDEX) {
            // Champs visibles uniquement dans la page INDEX
            return [
                IdField::new('id', 'ID'),
                TextField::new('username', 'Nom d\'utilisateur'),
                TextField::new('email', 'Email'),
                ArrayField::new('roles', 'Rôles'),
            ];
        }

        // Champs visibles pour les autres pages (NEW, EDIT, DETAIL)
        return [
            TextField::new('username', 'Nom d\'utilisateur'),
            TextField::new('email', 'Email'),
            TextField::new('password', 'Mot de passe')
                ->setHelp('Entrez un nouveau mot de passe pour le modifier, ou laissez vide pour conserver l\'ancien.')
                ->onlyOnForms(),
            ChoiceField::new('roles', 'Rôles')
                ->setChoices([
                    'Administrateur' => 'ROLE_ADMIN',
                    'Utilisateur' => 'ROLE_USER',
                    'Modérateur' => 'ROLE_MODERATOR',
                ])
                ->allowMultipleChoices(true),
        ];
    }

    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        $this->hashPasswordIfNeeded($entityInstance);
        parent::persistEntity($entityManager, $entityInstance);
    }

    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        $this->hashPasswordIfNeeded($entityInstance);
        parent::updateEntity($entityManager, $entityInstance);
    }

    private function hashPasswordIfNeeded($entityInstance): void
    {
        if (!$entityInstance instanceof User) {
            return;
        }

        // Vérifie si un nouveau mot de passe a été saisi
        if ($entityInstance->getPassword() && strlen($entityInstance->getPassword()) < 60) {
            // Hachage du mot de passe si non déjà haché
            $hashedPassword = $this->passwordHasher->hashPassword($entityInstance, $entityInstance->getPassword());
            $entityInstance->setPassword($hashedPassword);
        }
    }
}
