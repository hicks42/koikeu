<?php

namespace App\Form;

use App\Entity\Attachment;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;

class AttachmentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('attImageFile', VichImageType::class, [
            'required' => false,
            'download_uri' => false,
            'image_uri' => true,
            'allow_delete' => true,
            'delete_label' => 'Supprimer l\'image',
            'imagine_pattern' => 'square_thumbnail_small',
            'asset_helper' => true,
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Attachment::class,
        ]);
    }
}
