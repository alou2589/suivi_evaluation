<?php

namespace App\Form;

use App\Entity\InfoPerso;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Regex;

class InfoPersoForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('prenom')
            ->add('nom')
            ->add('sexe', ChoiceType::class, [
                'choices' => [
                    'Homme' => 'Homme',
                    'Femme' => 'Femme',
                ],
                'placeholder' => 'Sélectionnez le sexe',
            ])
            ->add('date_naissance', DateType::class, [
                'widget' => 'single_text',
                'html5' => true,
                'label' => 'Date de naissance',
            ])
            ->add('lieu_naissance', TextType::class, [
                'attr' => [
                    'placeholder' => 'Entrez le lieu de naissance',
                ],
                'constraints' => [
                    new Length([
                        'min' => 2,
                        'max' => 255,
                        'minMessage' => 'Le lieu de naissance doit comporter au moins {{ limit }} caractères',
                        'maxMessage' => 'Le lieu de naissance ne peut pas dépasser {{ limit }} caractères',
                    ]),
                ],
            ]
            )
            ->add('cin', TextType::class, [
                'attr' => [
                    'placeholder' => 'Entrez le numéro de la carte d\'identité nationale',
                ],
                'constraints' => [
                    new Length([
                        'min' => 13,
                        'max' => 15,
                        'minMessage' => 'Le numéro de la carte d\'identité doit comporter au moins {{ limit }} chiffres',
                        'maxMessage' => 'Le numéro de la carte d\'identité ne peut pas dépasser {{ limit }} chiffres',
                    ]),
                    new Regex([
                        'pattern' => '/^\d+$/',
                        'message' => 'Le numéro de la carte d\'identité doit contenir uniquement des chiffres',
                    ]),
                ],
            ])
            ->add('adresse', TextareaType::class, [
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
            ->add('telephone', TextType::class, [
                'attr' => [
                    'placeholder' => 'Entrez le numéro de téléphone',
                ],
                'constraints' => [
                    new Length([
                        'min' => 9,
                        'max' => 9,
                        'minMessage' => 'Le numéro de téléphone doit comporter au moins {{ limit }} chiffres',
                        'maxMessage' => 'Le numéro de téléphone ne peut pas dépasser {{ limit }} chiffres',
                    ]),
                    new Regex([
                        'pattern' => '/^\d+$/',
                        'message' => 'Le numéro de téléphone doit contenir uniquement des chiffres',
                    ]),
                ],
            ])
            ->add('email', EmailType::class, [
                'attr' => [
                    'placeholder' => 'Entrez l\'email',
                ],
                'constraints' => [
                    new Length([
                        'min' => 5,
                        'max' => 255,
                        'minMessage' => 'L\'email doit comporter au moins {{ limit }} caractères',
                        'maxMessage' => 'L\'email ne peut pas dépasser {{ limit }} caractères',
                    ]),
                ],
            ])
            ->add('situation_matrimoniale', ChoiceType::class, [
                'choices' => [
                    'Célibataire' => 'Célibataire',
                    'Marié(e)' => 'Marié(e)',
                    'Divorcé(e)' => 'Divorcé(e)',
                    'Veuf(ve)' => 'Veuf(ve)',
                ],
                'placeholder' => 'Sélectionnez la situation matrimoniale',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => InfoPerso::class,
        ]);
    }
}
