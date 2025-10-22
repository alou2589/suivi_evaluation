<?php

namespace App\Form;

use App\Entity\Direction;
use Dom\Text;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DirectionForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('type_direction', ChoiceType::class, [
                'choices' => [
                    'Direction' => 'direction',
                    'Agence' => 'agence',
                    'Projet' => 'projet',
                    'Programme' => 'programme',
                ],
                'placeholder' => 'Select a type of direction',
                'required' => true,
                'attr' => [
                    'class' => 'form-select',
                ],
            ])
            ->add('nom_direction')
            ->add('description', TextareaType::class, [
                'required' => false,
                'attr' => [
                    'rows' => 5,
                    'placeholder' => 'Enter a brief description of the direction',
                ],
                'autocomplete' => true,
                'tom_select_options' => [
                    'create' => true,
                    'createOnBlur' => true,
                    'delimiter' => ',',
                ],

            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Direction::class,
        ]);
    }
}
