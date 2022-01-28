<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Form\RoleType;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureFields(string $pageName): iterable
    {
        $roles = [
            'Utilisateur' => 'ROLE_USER',
            'Administrateur' => 'ROLE_ADMIN'
        ];
        // dd(array_flip($roles));
        return [
            TextField::new('first_name', 'Nom'),
            TextField::new('last_name', 'Prénom'),
            EmailField::new('email', 'Email'),
            ChoiceField::new('roles', 'Role')
                ->allowMultipleChoices(false)
                // ->renderExpanded(true)
                ->setFormType(RoleType::class)
                ->setChoices($roles),
            BooleanField::new('is_verified', 'Vérifié'),
            DateTimeField::new('created_at', 'Date de création')
        ];
    }
}
