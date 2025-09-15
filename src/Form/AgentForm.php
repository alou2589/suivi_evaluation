<?php

namespace App\Form;

use App\Entity\Agent;
use App\Entity\InfoPerso;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Regex;

class AgentForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('identification', EntityType::class, [
                'class' => InfoPerso::class,
                'choice_label' => function (InfoPerso $infoPerso) {
                    if($infoPerso->getId()== 0) {
                        return null;
                    }
                    return $infoPerso->getPrenom() . ' ' . $infoPerso->getNom(). ' '. $infoPerso->getTelephone();
                },
                'required' => false,
                'attr' => [
                    'data-placeholder' => 'Selectionner un agent',
                    'class' => 'js-example-basic-single',
                ],
            ])
            ->add('matricule', TextType::class, [
                'attr' => [
                    'placeholder' => 'Entrez matricule',
                ],
                'constraints' => [
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Matricule must be at least {{ limit }} characters long',
                        'max' => 10,
                        'maxMessage' => 'Matricule cannot be longer than {{ limit }} characters',
                    ]),
                    new Regex([
                        'pattern' => '/^[0-9]{6,10}[A-Z]$/',
                        'message' => 'Matricule doit comporter entre 6 et 10 digits suivi d\' 1 lettre en majuscule (e.g., 123456A)',
                    ]),
                ],
            ])
            ->add('fonction')
            ->add('cadre_statuaire',ChoiceType::class, [
                'choices' => [
                    'Fonctionnaire' => 'fonctionnaire',
                    'Non Fonctionnaire' => 'non fonctionnaire',
                    'Contractuel' => 'contractuel',
                    'Stagiaire' => 'stagiaire',
                ],
                'placeholder' => 'Selectionnez un cadre statuaire',
                'required' => true,
                'attr' => [
                    'class' => 'form-select',
                ],
            ])
            ->add('hierarchie',ChoiceType::class, [
                'choices' => [
                    'A1' => 'A1',
                    'A2' => 'A2',
                    'A3' => 'A3',
                    'B1' => 'B1',
                    'B2' => 'B2',
                    'B4' => 'B4',
                    'C1' => 'C1',
                    'C2' => 'C2',
                    'C3' => 'C3',
                    'D' => 'D',
                    'E' => 'E',
                    'NI'=> 'NI',
                ],
                'placeholder' => 'Selectionnez une hiérarchie',
                'required' => true,
                'attr' => [
                    'class' => 'form-select',
                ],
            ])
            ->add('grade', ChoiceType::class,[
                'choices' => [
                    '1ère classe' => '1ère classe',
                    '2ème classe'=> '2ème classe',
                    '3ème classe'=> '3ème classe',
                    '4ème classe'=> '4ème classe',
                    'NI'=> 'NI',
                ],
                'placeholder' => 'Selectionner grade',
                'required' => 'required',
                'attr' => [
                    'class' => 'form-select',
                ]
            ])
            ->add('echelon', ChoiceType::class,[
                'choices' => [
                    '1er echelon' => '1er echelon',
                    '2eme echelon'=> '1er echelon',
                    'NI'=> 'NI',
                ],
                'placeholder' => 'Selectionner échelon',
                'required' => 'required',
                'attr' => [
                    'class' => 'form-select',
                ]
            ])
            ->add('numero_decision_contrat')

            ->add('decision_contrat', ChoiceType::class,[
                'choices' => [
                    'Décision' => 'décision',
                    'Contrat'=> 'contrat',
                ],
                'placeholder' => 'Décision ou Contrat',
                'required' => 'required',
                'attr' => [
                    'class' => 'form-select',
                ]
            ])
            ->add('date_recrutement', DateType::class, [
                'widget' => 'single_text',
                'html5' => true,
                'label' => 'Date de recrutement',
            ])
            ->add('status', ChoiceType::class,[
                'choices' => [
                    'En Service' => 'En Service',
                    'Affecté'=> 'Affecté',
                    'Retraité'=> 'Retraité',
                ],
                'placeholder' => 'Selectionner status',
                'required' => 'required',
                'attr' => [
                    'class' => 'form-select',
                ]
            ])
            ->add('banque', ChoiceType::class,[
                'choices' => [
                    'SGBS'=> 'SGBS',
                    'CNCAS'=> 'CNCAS',
                    'BICIS'=> 'BICIS',
                    'Banque Islamique'=> 'Banque Islamique',
                    'Banque Atlantique'=> 'Banque Atlantique',
                ],
                'placeholder' => 'Selectionner Banque',
                'required' => 'required',
                'attr' => [
                    'class' => 'form-select',
                ]
            ])
            ->add('numeroCompte')

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Agent::class,
        ]);
    }
}
