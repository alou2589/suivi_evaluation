<?php

namespace App\Form;

use App\Entity\Service;
use App\Entity\SousStructure;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SousStructureType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $builder
            ->add(
            'nom_sous_structure')
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
            ->add('service_rattache', EntityType::class, [
                'class' => Service::class,
                'choice_label' => 'nom_service',
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
            'data_class' => SousStructure::class,
        ]);
    }
}
