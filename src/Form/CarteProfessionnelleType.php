<?php

namespace App\Form;

use App\Entity\Affectation;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use App\Entity\CarteProfessionnelle;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CarteProfessionnelleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('photo_agent', FileType::class,[
                'label'=>false,
                'mapped'=>false,
                'required'=>false,
                'constraints'=>[
                    new File([
                        'maxSize'=>'2048k',
                        'mimeTypes'=>[
                            'image/png',
                            'image/jpeg',
                            'image/jpg',
                        ],
                        'mimeTypesMessage'=>'Charger une image',
                    ])
                ]
            ])
            ->add('date_delivrance', DateType::class, [
                'widget'=>'single_text',
                'label'=>'Date de Délivrance',
                'attr'=> ['class'=> 'js-datepicker'],
            ])
            ->add('identite', EntityType::class, [
                'class' => Affectation::class,
                'choice_label' => function (Affectation $affectation) {
                    if($affectation->getId()== 0) {
                        return null;
                    }
                    return $affectation->getAgent()->getIdentification()->getPrenom() . ' ' . $affectation->getAgent()->getIdentification()->getNom(). ' - '.$affectation->getAgent()->getMatricule();
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
            'data_class' => CarteProfessionnelle::class,
        ]);
    }
}
