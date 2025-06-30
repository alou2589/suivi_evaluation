<?php

namespace App\Form;

use App\Entity\Affectation;
use App\Entity\Agent;
use App\Entity\Poste;
use App\Entity\Service;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AffectationForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('date_debut', null, [
                'widget' => 'single_text',
            ])
            ->add('date_fin', null, [
                'widget' => 'single_text',
                'required' => false,
            ])
            ->add('agent', EntityType::class, [
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
            ->add('poste', EntityType::class, [
                'class' => Poste::class,
                'choice_label' => 'nom_poste',
                'required' => false,
                'attr' => [
                    'data-placeholder' => 'Select a poste',
                    'class' => 'js-example-basic-single',
                ],
            ])
            ->add('service',  EntityType::class, [
                'class' => Service::class,
                'choice_label' => 'description',
                'required' => false,
                'attr' => [
                    'data-placeholder' => 'Select a service',
                    'class' => 'js-example-basic-single',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Affectation::class,
        ]);
    }
}
