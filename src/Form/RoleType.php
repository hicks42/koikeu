<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class RoleType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    parent::buildForm($builder, $options);
    $builder
      ->addModelTransformer(new CallbackTransformer(
        function ($originalRoles) {
          return ($originalRoles) ? $originalRoles[0] : null;
        },
        function ($submmitedRoles) {
          return array_filter([$submmitedRoles]);
        }
      ));
  }
  public function getParent()
  {
    return ChoiceType::class;
  }
}
