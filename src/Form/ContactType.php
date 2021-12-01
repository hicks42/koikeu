<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Prenom', TextType::class,[
                'label' => 'Votre prénom :',
                'attr' => [
                    'class' =>'control-form'
                ]
            ])
            ->add('Nom', TextType:: class, [
                'label' => 'Vote nom :'
            ])
            ->add('Sujet', TextType::class)
            ->add('Phone', TelType::class,[
                'label' => 'Téléphone :'
            ])
            ->add('Email', EmailType::class,[
                'label' => 'Voter e-mail :'
            ])
            ->add('Message',TextareaType::class)


            ->add('Envoyer', SubmitType::class, [
                'attr' => [
                    'class' => 'btn-primary mt-2'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
