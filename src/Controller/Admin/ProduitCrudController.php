<?php

namespace App\Controller\Admin;

use App\Entity\Produit;
use App\Entity\Attachment;
use App\Form\AttachmentType;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class ProduitCrudController extends AbstractCrudController
{

    public static function getEntityFqcn(): string
    {
        return Produit::class;
    }

    public function configureFields(string $pageName ): iterable
    {

        return [
            AssociationField::new('user')->hideOnForm(),
            DateTimeField::new('createdAt')->onlyOnDetail(),
            ImageField::new('imageName')
                ->setBasePath('images/products')
                ->setUploadDir('public/images/products')
                ->setRequired(false)
                ->setUploadedFileNamePattern('[randomhash].[extension]'),
            TextField::new('name'),
            SlugField::new('slug')->setTargetFieldName('name')->onlyOnForms(),
            AssociationField::new('category'),
            TextareaField::new('description'),
            MoneyField::new('price')->setCurrency('EUR'),
            CollectionField::new('attachments')
                ->setEntryType(AttachmentType::class)
                ->setFormTypeOption('by_reference', false)
                ->onlyOnForms(),
            CollectionField::new('attachments')
                ->setTemplatePath('attachment.html.twig')
                ->onlyOnDetail(),
        ];
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ;
    }
}
