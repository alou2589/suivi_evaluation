<?php

namespace App\Form;

use App\Entity\MatosInformatique;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MatosInformatiqueType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('type_matos')
            ->add('marque_matos')
            ->add('modele_matos')
            ->add('sn_matos')
            ->add('date_reception')
            ->add('specification')
            ->add('createdAt', null, [
                'widget' => 'single_text',
            ])
            ->add('updatedAt', null, [
                'widget' => 'single_text',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => MatosInformatique::class,
        ]);
    }
}
