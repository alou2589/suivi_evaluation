<?php

namespace App\Form;

use App\Entity\MarqueMatos;
use App\Entity\MatosInformatique;
use App\Entity\TypeMatos;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class MatosInformatiqueType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('type_materiel', EntityType::class, [
                'class' => TypeMatos::class,
                'choice_label' => 'nom_type',
                'placeholder' => 'Sélectionnez un type de matériel',
            ])
            ->add('marque_matos', EntityType::class, [
                'class' => MarqueMatos::class,
                'choice_label' => 'nom_marque',
                'required' => false,
                'attr' => [
                    'data-placeholder' => 'Selectionnez une marque',
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
