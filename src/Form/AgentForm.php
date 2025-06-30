<?php

namespace App\Form;

use App\Entity\Agent;
use App\Entity\Service;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Regex;

class AgentForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('prenom')
            ->add('nom')
            ->add('matricule', TextType::class, [
                'attr' => [
                    'placeholder' => 'Enter email',
                ],
                'constraints' => [
                    new Length([
                        'min' => 5,
                        'minMessage' => 'Email must be at least {{ limit }} characters long',
                        'max' => 7,
                        'maxMessage' => 'Email cannot be longer than {{ limit }} characters',
                    ]),
                    new Regex([
                        'pattern' => '/^\d{6}[A-Z]{1}$/',
                        'message' => 'Matricule doit comporter 6 digits suivi d\' 1 lettre en majuscule (e.g., 123456A)',
                    ]),
                ],
            ])
            ->add('fonction')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Agent::class,
        ]);
    }
}
