<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class CategoryCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Category::class;
    }


    public function configureFields(string $pageName): iterable
    {
        $range = (range(1, 10));
        // dd($range);
        return [
            TextField::new('name', 'Nom de la catégorie')
                ->setColumns(6),
            // IntegerField::new('display', 'Ordre d\'affichage')
            //     ->setColumns(6),
            ChoiceField::new('display', 'Ordre d\'affichage')
                ->setChoices(array_flip($range))
                ->setColumns(6),
            SlugField::new('slug')
                ->setTargetFieldName('name')
                ->onlyOnForms()
                ->setColumns(6),
            ImageField::new('imageName', 'Image')
                ->setBasePath('images/products')
                ->setUploadDir('public/images/products')
                ->setRequired(false)
                ->setUploadedFileNamePattern('[randomhash].[extension]')
                ->setColumns(6),

        ];
    }
}
