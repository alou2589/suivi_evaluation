<?php

namespace App\Form;

use App\Entity\Affectation;
use App\Entity\Technicien;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Repository\AffectationRepository;

class TechnicienType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('info_technicien', EntityType::class, [
                'class' => Affectation::class,
                'query_builder' => function (AffectationRepository $repo) {
                    return $repo->createQueryBuilder('a')
                        ->join('a.service', 's')
                        ->where('s.nom_service = :nom')
                        ->setParameter('nom', 'CI');
                },
                'choice_label' => function (Affectation $affectation) {
                    return $affectation->getAgent()->getIdentification()->getPrenom()
                         . ' ' . $affectation->getAgent()->getIdentification()->getNom()
                         . ' - ' . $affectation->getAgent()->getMatricule();
                },
                'placeholder' => 'Sélectionnez un technicien',
                'attr' => [
                    'class' => 'form-control js-example-basic-single',
                    'id' => 'agent',
                    'required' => 'required',
                ],
            ])
        ;

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Technicien::class,
        ]);
    }
}
