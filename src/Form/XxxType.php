<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class XxxType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    parent::buildForm($builder, $options);
    $builder
      ->addModelTransformer(new CallbackTransformer(
        function ($originalXxxs) {
          return ($originalXxxs) ? $originalXxxs[0] : null;
        },
        function ($submmitedXxxs) {
          return array_filter([$submmitedXxxs]);
        }
      ));
  }
  public function getParent()
  {
    return ChoiceType::class;
  }
}
