<?php

namespace App\Form;

use App\Entity\Direction;
use App\Entity\Service;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ServiceForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('type_service', ChoiceType::class, [
                'choices' => [
                    'Service' => 'service',
                    'Cellule' => 'cellule',
                    'Autre' => 'autre',
                ],
                'placeholder' => 'Select a type of service',
                'required' => true,
                'attr' => [
                    'class' => 'form-select',
                ],
            ])
            ->add(
            'nom_service')
            ->add('description', TextareaType::class, [
                'required' => false,
                'attr' => [
                    'rows' => 5,
                    'placeholder' => 'Enter a brief description of the service',
                ],
                'autocomplete' => true,
                'tom_select_options' => [
                    'create' => true,
                    'createOnBlur' => true,
                    'delimiter' => ',',
                ],

            ])
            ->add('structure_rattachee', EntityType::class, [
                'class' => Direction::class,
                'choice_label' => 'nom_direction',
                'required' => false,
                'attr' => [
                    'data-placeholder' => 'Select a direction',
                    'class' => 'js-example-basic-single',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Service::class,
        ]);
    }
}
