<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Assert;


class UploadCniType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('cniFile', FileType::class, [
            'label'=>' Carte d\'identité (PDF,JPG,JPEG,PNG)',
            'mapped'=> false,
            'required'=>true,
            'constraints'=>[
                new Assert\File([
                    'maxSize'=>'10M',
                    'mimeTypes'=>[
                        'application/pdf',
                        'image/jpeg','image/png'
                    ],
                ]),
            ],
        ]);
    }
}
