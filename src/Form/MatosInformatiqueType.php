<?php

namespace App\Form;

use App\Entity\MarqueMatos;
use App\Entity\MatosInformatique;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class MatosInformatiqueType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('type_matos', ChoiceType::class, [
                'choices' => [
                    'Ordinateur Portable' => 'ordinateur portable',
                    'Ordinateur Fixe' => 'ordinateur fixe',
                    'Imprimante' => 'imprimante',
                    'Scanner' => 'scanner',
                    'Autre' => 'autre',
                ],
                'placeholder' => 'Sélectionnez un type de matériel',
            ])
            ->add('marque_matos', EntityType::class, [
                'class' => MarqueMatos::class,
                'choice_label' => 'nom_marque',
                'required' => false,
                'attr' => [
                    'data-placeholder' => 'Select une marque',
                    'class' => 'js-example-basic-single',
                ],
            ])
            ->add('modele_matos', TextType::class, [
                'label' => 'Modèle du matériel',
                'required' => false,
            ])
            ->add('sn_matos', TextType::class, [
                'label' => 'Numéro de série',
                'required' => false,
            ])
            ->add('date_reception', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Date de réception',
                'required' => false,
            ])
            ->add('specification', FileType::class, [
                'label' => 'Spécifications',
                'required' => false,
                'attr' => ['accept' => '.pdf,.doc,.docx,.txt'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => MatosInformatique::class,
        ]);
    }
}
