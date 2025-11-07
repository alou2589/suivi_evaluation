<?php

namespace App\Form;

use App\Entity\Affectation;
use App\Entity\Attribution;
use App\Entity\MatosInformatique;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;


class AttributionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('date_attribution',DateType::class, [
                'widget'=>'single_text',
                'label'=>'Date d\'attribution',
                'attr'=> ['class'=> 'js-datepicker'],
            ])
            ->add('affectaire', EntityType::class, [
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
            ->add('materiel', EntityType::class, [
                'class' => MatosInformatique::class,
                'choice_label' => function (MatosInformatique $materiel) {
                    if($materiel->getId()== 0) {
                        return null;
                    }
                    return $materiel->getTypeMatos() . ' ' . $materiel->getMarqueMatos()->getNomMarque() . ' ' . $materiel->getModeleMatos().' '.$materiel->getSnMatos();
                },
                'required' => false,
                'attr' => [
                    'data-placeholder' => 'Selectionner un matériel',
                    'class' => 'js-example-basic-single',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Attribution::class,
        ]);
    }
}
