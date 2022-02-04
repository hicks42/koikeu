<?php

namespace App\Controller\Admin;

use App\Entity\Order;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class OrderCrudController extends AbstractCrudController
{
  public static function getEntityFqcn(): string
  {
    return Order::class;
  }

  public function configureActions(Actions $actions): Actions
  {
    return $actions
      ->add('index', 'detail')
      ->remove(Crud::PAGE_INDEX, Action::NEW)
      ->remove(Crud::PAGE_INDEX, Action::EDIT)
      ->remove(Crud::PAGE_INDEX, Action::DELETE)
      ->remove(Crud::PAGE_DETAIL, Action::EDIT)
      ->remove(Crud::PAGE_DETAIL, Action::DELETE);
  }

  public function configureCrud(Crud $crud): Crud
  {
    return $crud
      ->setDefaultSort(['id' => 'DESC'])
      ->showEntityActionsInlined()
      ->setPaginatorPageSize(10);
  }

  public function configureFields(string $pageName): iterable
  {
    return [
      IdField::new('id'),
      DateField::new('createdAt', 'Date de commande'),
      TextField::new('user.fullname', 'Client'),
      MoneyField::new('total', 'Total produits')->setCurrency('EUR'),
      TextField::new('carrierName', 'Transporteur'),
      MoneyField::new('carrierPrice', 'Frais de port')->setCurrency('EUR'),
      BooleanField::new('isPaid', 'payée'),
      ArrayField::new('orderDetails', 'liste des produits')->hideOnIndex()

    ];
  }
}
