<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints as Assert;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            
        ->add('fullName',TextType::class, [
            'attr'=>[
            'class'=>'form-control',
            'minlength'=>'2',
            'maxlength'=>'50',
        ],
        'label'=>'Nom prenom',
        'label_attr'=>[
            'class'=>'form-label mt-4',
        ],
        'constraints' =>[
            new Assert\Length(min:2, max:50),
            new Assert\NotBlank()
        ]
        ])
        ->add('pseudo', TextType::class, [
            'attr'=>[
            'class'=>'form-control',
            'minlength'=>'2',
            'maxlength'=>'50',
        ],
        'required'=>false,
        'label'=>'Pseudo (Falcultatif)',
        'label_attr'=>[
            'class'=>'form-label mt-4',
        ],
        'constraints' =>[
            new Assert\Length(min:2, max:50),
            new Assert\NotBlank()
        ]
        ])
            ->add('submit',SubmitType::class,[
                'attr'=>[
                    'class'=>'btn btn-primary my-4',
                ],
                'label'=>'Inscription'
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
