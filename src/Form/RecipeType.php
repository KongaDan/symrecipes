<?php

namespace App\Form;


use App\Entity\Recipe;
use App\Entity\Ingredient;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\RangeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class RecipeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('name', TextType::class, [
            'attr'=>[
                'class'=>'form-control',
                'minlength'=>'2',
                'maxlength'=>'50',
            ],
            'label'=>'Nom',
            'label_attr'=>[
                'class'=>'form-label mt-4',
            ],
            'constraints' =>[
                new Assert\Length(min:2, max:50),
                new Assert\NotBlank()
            ]])
            ->add('time', IntegerType::class,[
                'attr'=>[
                    'class'=>'form-control',
                    'min'=>1,
                    'max'=>1140,
                ],
                'label'=>'Temps',
                'label_attr'=>[
                    'class'=>'form-label mt-4',
                ],
                'required'=>false,
                'constraints'=>[
                    new Assert\Positive(),
                    new Assert\LessThan(1441)
                ]
            ])
            ->add('nbPeople', IntegerType::class, [
                'attr'=>[
                    'class'=>'form-control',
                ],
                'required'=>false,
                'label'=>'Nombre de personne',
                'label_attr'=>[
                    'class'=>'form-label mt-4',
                ],
                'constraints'=>[
                    new Assert\Positive(),
                    new Assert\LessThan(51)
                ]
            ])
            ->add('difficulty', RangeType::class,[
                'attr'=>[
                    'class'=>'form-range',
                    'min'=>1,
                    'max'=>5
                ],
                'required'=>false,
                'label'=>'Difficulte',
                'label_attr'=>[
                    'class'=>'form-label mt-4',
                ]
            ])
            ->add('description', TextareaType::class,[
                'attr'=>[
                    'class'=>'form-control',
                ],
                'label'=>'Description',
                'label_attr'=>[
                    'class'=>'form-label mt-4',
                ],
            ])
            ->add('price', MoneyType::class, [
                'attr'=>[
                    'class'=>'form-control',
                ],
                'required'=>false,
                'label'=>'Prix',
                'label_attr'=>[
                    'class'=>'form-label mt-4',
                ],
                'constraints' =>[
                    new Assert\Positive(),
                    new Assert\LessThan(1001)
                ]
            ])
            ->add('isFavorite', CheckboxType::class, [
                'attr'=>[
                    'class'=>'form-check-input',
                ],
                'required'=>false,
                'label'=>'Favoris',
                'label_attr'=>[
                    'class'=>'form-label',
                ],
                'constraints'=>[
                    new Assert\NotNull()
                ]
            ])
            
            ->add('ingredients', EntityType::class, [
                'class' => Ingredient::class,
                'choice_label' => 'name',
                'query_builder' => function (EntityRepository $er): QueryBuilder {
                return $er->createQueryBuilder('i')
                ->orderBy('i.name', 'ASC');
        },
                'multiple' => true,
                'expanded'=>true,
                'attr'=>[
                    'class'=>'form-check-input',
                ],
                'label'=>'Les ingredients'
            ])
            ->add('submit',SubmitType::class, [
                'attr'=>[
                    'class'=>'btn btn-primary mt-4',
                ],
                'label'=>'Creer ma recette'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Recipe::class,
        ]);
    }
}
