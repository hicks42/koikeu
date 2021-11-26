<?php

namespace App\Form;

use App\Entity\Produit;
use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class ProduitType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    $builder
      ->add('imageFile', VichImageType::class, [
        'label' => 'Image',
        'required' => false,
        'allow_delete' => true,
        'delete_label' => 'Supprimer l\'image',
        'download_label' => 'Télécharger',
        'download_uri' => false,
        'image_uri' => true,
        'imagine_pattern' => 'square_thumbnail_small',
        'asset_helper' => true,
      ])
      ->add('attachments', CollectionType::class, [
        'entry_type' => AttachmentType::class,
        'allow_add' => true,
        'allow_delete' => true,
        'prototype' => true,
        'by_reference' => false,
      ])
      ->add('name')
      ->add('description')
      ->add('category', EntityType::class, [
        'class' => Category::class,
        'choice_label' => function ($category) {
          return $category->getName();
        }
      ])
      ->add('price', MoneyType::class, [
        'divisor' => 100,
        'currency' => 'EUR',
      ]);
  }

  public function configureOptions(OptionsResolver $resolver)
  {
    $resolver->setDefaults([
      'data_class' => Produit::class,
    ]);
  }
}
