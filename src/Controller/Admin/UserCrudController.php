<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureFields(string $pageName): iterable
    {
        yield TextField::new('email', 'E-mail');
        
        yield TextField::new('password', 'Mot de passe')
            ->setFormType(PasswordType::class)
            ->hideOnIndex(); // Cache le champ sur la liste des utilisateurs

        yield ChoiceField::new('roles', 'Rôles')
            ->setChoices([
                'Admin' => 'ROLE_ADMIN',
                'Utilisateur' => 'ROLE_USER',
                // Ajoutez d'autres rôles au besoin
            ])
            ->allowMultipleChoices();

        // Ajoutez d'autres champs au besoin

        return parent::configureFields($pageName);
    }
}
