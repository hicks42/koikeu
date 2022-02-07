<?php

namespace App\Controller\Admin;

use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Router\CrudUrlGenerator;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;

class OrderCrudController extends AbstractCrudController
{

  private $em;
  private $crudgen;
  private $admingen;

  public function __construct(EntityManagerInterface $em, CrudUrlGenerator $crudgen, AdminUrlGenerator $admingen)
  {
    $this->em = $em;
    $this->crudgen = $crudgen;
    $this->admingen = $admingen;
  }

  public static function getEntityFqcn(): string
  {
    return Order::class;
  }

  public function configureActions(Actions $actions): Actions
  {
    $updatePreparation = Action::new('updatePreparation', 'Préparation en cours ', ' fas fa-box-open')->linkToCrudAction('updatePreparation');
    $updateDelivery = Action::new('updateDelivery', 'Livraison en cours ', ' fas fa-truck')->linkToCrudAction('updateDelivery');
    return $actions
      ->add('index', 'detail')
      ->add('detail', $updatePreparation)
      ->add('detail', $updateDelivery)
      ->remove(Crud::PAGE_INDEX, Action::NEW)
      ->remove(Crud::PAGE_INDEX, Action::EDIT)
      ->remove(Crud::PAGE_INDEX, Action::DELETE)
      ->remove(Crud::PAGE_DETAIL, Action::EDIT)
      ->remove(Crud::PAGE_DETAIL, Action::DELETE);
  }

  public function updatePreparation(AdminContext $context)
  {
    $order = $context->getEntity()->getInstance();
    $order->setStatus(2);
    $this->em->flush();

    $this->addFlash('notice', "<span style='color:green'><strong>La commande" . $order->getReference() . "est en cours de préparation</strong></span>");

    $url = $this->admingen
      ->setController(OrderCrudController::class)
      ->setAction(Action::INDEX)
      ->generateUrl();

    return $this->redirect($url);
  }

  public function updateDelivery(AdminContext $context)
  {
    $order = $context->getEntity()->getInstance();
    $order->setStatus(3);
    $this->em->flush();

    $this->addFlash('notice', "<span style='color:green'><strong>La commande" . $order->getReference() . "est en cours de livraison</strong></span>");

    $url = $this->admingen
      ->setController(OrderCrudController::class)
      ->setAction(Action::INDEX)
      ->generateUrl();

    return $this->redirect($url);
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
      TextEditorField::new('delivery', 'Adresse de livraison')
        ->setTemplatePath('admin/fields/adresse.html.twig')
        ->onlyOnDetail(),
      MoneyField::new('total', 'Total produits')->setCurrency('EUR'),
      TextField::new('carrierName', 'Transporteur'),
      MoneyField::new('carrierPrice', 'Frais de port')->setCurrency('EUR'),
      ChoiceField::new('status', 'Etat')->setChoices([
        'Non payée' => 0,
        'Payée' => 1,
        'Préparation en cours' => 2,
        'Livraison en cours' => 3,
      ]),
      ArrayField::new('orderDetails', 'liste des produits')->hideOnIndex()

    ];
  }
}
