<?php

namespace App\Form;

use App\Entity\Maintenance;
use App\Entity\MatosInformatique;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class MaintenanceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('date_maintenance', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Date maintenance',
                'required' => false,
            ])
            ->add('status_matos', ChoiceType::class, [
                'choices' => [
                    'En bon état' => 'en bon état',
                    'En panne' => 'en panne',
                    'En réparation' => 'en réparation',
                    'Hors service' => 'hors service',
                ],
                'placeholder' => 'Sélectionnez un statut',
            ])
            ->add('fiche_maintenance', FileType::class, [
                'label' => 'Télécharger la fiche de maintenance',
                'attr' => ['accept' => '.pdf,.doc,.docx,.jpg,.jpeg,.png'],
                'required' => false,
            ])
            ->add('prestataire', TextType::class, [
                'label' => 'Prestataire',
                'required' => false,
            ])
            ->add('materiel', EntityType::class, [
                'class' => MatosInformatique::class,
                'choice_label' => function (MatosInformatique $materiel) {
                    if($materiel->getId()== 0) {
                        return null;
                    }
                    return $materiel->getTypeMatos() . ' ' . $materiel->getMarqueMatos() . ' ' . $materiel->getModeleMatos().' '.$materiel->getSnMatos();
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
            'data_class' => Maintenance::class,
        ]);
    }
}
