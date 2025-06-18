<?php

namespace App\Form;

use App\Entity\Agent;
use App\Entity\Programme;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Regex;

class ProgrammeForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom_programme', TextType::class, [
                'attr' => [
                    'placeholder' => 'Enter programme name',
                ],
            ])
            ->add('responsable_programme', EntityType::class, [
                'class' => Agent::class,
                'choice_label' => function (Agent $agent) {
                    if($agent->getId()== 0) {
                        return null;
                    }
                    return $agent->getPrenom() . ' ' . $agent->getNom();
                },
                'required' => false,
                'attr' => [
                    'data-placeholder' => 'Selectionner un responsable',
                    'class' => 'js-example-basic-single',
                ],
            ])
            ->add('annee_programme', NumberType::class, [
                'label'=>'Année',
                'attr' => [
                    'pattern' => '\d{4}',
                    'maxlength' => 4,
                    'placeholder' => 'ex: 2025',
                ],
                'constraints' => [
                    new Regex([
                        'pattern' => '/^\d{4}$/',
                        'message' => 'L\'année doit être un nombre à 4 chiffres (ex: 2025)',
                    ]),
                ],
            ])
            ->add('cout_programme', TextType::class, [
                'attr' => [
                    'placeholder' => 'Enter le cout du programme',
                ],
                'constraints' => [
                    new Regex([
                        'pattern' => '/^\d+(\.\d{1,2})?$/',
                        'message' => 'Le coût doit être un nombre valide (ex: 1000 ou 1000.50)',
                    ]),
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Programme::class,
        ]);
    }
}
