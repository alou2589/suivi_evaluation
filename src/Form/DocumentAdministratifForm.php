<?php

namespace App\Form;

use App\Entity\Agent;
use App\Entity\DocumentAdministratif;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DocumentAdministratifForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('type_doc', ChoiceType::class, [
                'choices' => [
                    'Curriculum Vitae' => 'Curriculum Vitae',
                    'Certificat de nationalité' => 'Certificat de nationalité',
                    'Certificat de mariage' => 'Certificat de mariage',
                    'Attestation de travail' => 'Attestation de travail',
                    'Attestation de congé' => 'Attestation de congé',
                    'Attestation de cessation' => 'Attestation de cessation',
                    'Autre' => 'Autre',
                ],
                'placeholder' => 'Sélectionnez un type de document',
            ])
            ->add('nom_doc', TextType::class, [
                'label' => 'Nom du document',
                'attr' => ['placeholder' => 'Entrez le nom du document'],
            ])
            ->add('document', FileType::class, [
                'label' => 'Télécharger le document',
                'attr' => ['accept' => '.pdf,.doc,.docx,.jpg,.jpeg,.png'],
                'required' => false,
            ])
            ->add('agent', EntityType::class, [
                'class' => Agent::class,
                'choice_label' => function (Agent $agent) {
                    if($agent->getId()== 0) {
                        return null;
                    }
                    return $agent->getIdentification()->getPrenom() . ' ' . $agent->getIdentification()->getNom(). ' - '.$agent->getMatricule();
                },
                'required' => false,
                'attr' => [
                    'data-placeholder' => 'Selectionner un agent',
                    'class' => 'js-example-basic-single',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => DocumentAdministratif::class,
        ]);
    }
}
