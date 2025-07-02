<?php

namespace App\Form;

use App\Entity\Action;
use App\Entity\Programme;
use App\Entity\Agent;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Regex;

class ActionForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom_action', TextType::class, [
                'attr' => [
                    'placeholder' => 'Enter action name',
                ],
            ])
            ->add('code_action')
            ->add('cout_action', TextType::class, [
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
            ->add('programme', EntityType::class, [
                'class' => Programme::class,
                'choice_label' => function (Programme $programme) {
                    if($programme->getId()== 0) {
                        return null;
                    }
                    return $programme->getCodeProgramme() . ' ' . $programme->getNomProgramme();
                },
                'required' => false,
                'attr' => [
                    'data-placeholder' => 'Selectionner un programme',
                    'class' => 'js-example-basic-single',
                ],
            ])
            ->add('responsable_action', EntityType::class, [
                'class' => Agent::class,
                'choice_label' => function (Agent $agent) {
                    if($agent->getId()== 0) {
                        return null;
                    }
                    return $agent->getIdentification()->getPrenom() . ' ' . $agent->getIdentification()->getNom();
                },
                'required' => false,
                'attr' => [
                    'data-placeholder' => 'Selectionner un responsable',
                    'class' => 'js-example-basic-single',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Action::class,
        ]);
    }
}
