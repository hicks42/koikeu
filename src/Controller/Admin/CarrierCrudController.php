<?php

namespace App\Controller\Admin;

use App\Entity\Carrier;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\EntityFilter;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class CarrierCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Carrier::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Constructeur')
            ->setEntityLabelInPlural('Constructeurs');
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name', 'Transporteur')
                ->setColumns(6),
            TextareaField::new('description', 'Description')
                ->setColumns(6),
            MoneyField::new('price', 'Prix')
                ->setColumns(6)
                ->setCurrency('EUR'),
        ];
    }
    /**/
}
