<?php

namespace App\Form;

use Assert\Email;
use App\Entity\User;
use App\Entity\Agent;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('pseudo', TextType::class)
            ->add('email', EmailType::class, [
                'label'=>'Entrez votre email',
                'attr'=>['placetype: holder' => 'prenom.nom@industriecommerce.gouv.sn'],
                'constraints' => [
                    new Assert\Email([
                        'message' => 'L\'adresse e-mail n\'a pas un format valide.'
                    ]),
                    new Assert\Regex([
                        // vérifie que l'email se termine exactement par @industriecommerce.gouv.sn
                        'pattern' => '/@industriecommerce\.gouv\.sn$/i',
                        'message' => 'L\'email doit se terminer par "@industriecommerce.gouv.sn".',
                    ]),
                ],
            ])
            ->add('roles', ChoiceType::class, [
                'multiple'=>true,
                'choices' => [
                    'Super Admin' => 'ROLE_SUPER_ADMIN',
                    'Admin' => 'ROLE_ADMIN',
                    'RH Admin' => 'ROLE_RH_ADMIN',
                    'Info Admin' => 'ROLE_INFO_ADMIN',
                    'Gestion Admin' => 'ROLE_GESTION_ADMIN',
                    'Parc Auto Admin' => 'ROLE_PARC_AUTO_ADMIN',
                    'Technicien' => 'ROLE_TECHNICIEN',
                    'Utilisateur' => 'ROLE_USER',
                ],
                'attr'=>['class'=>'js-example-basic-multiple']
            ])
            ->add('agent', EntityType::class, [
                'class' => Agent::class,
                'choice_label' => function (Agent $agent) {
                    if($agent->getId()== 0) {
                        return null;
                    }
                    return $agent->getIdentification()->getPrenom() . ' ' . $agent->getIdentification()->getNom().' - '.$agent->getMatricule();
                },
                'required' => false,
                'attr' => [
                    'data-placeholder' => 'Selectionner un responsable',
                    'class' => 'js-example-basic-single',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
