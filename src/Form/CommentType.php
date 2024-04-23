<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Comment;
use App\Entity\Product;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class CommentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // ->add('createdAt', null, [
            //     'widget' => 'single_text',
            // ])
            ->add('rating', IntegerType::class, [
                'label'=>'note sur 5',
                'attr'=> [
                    'min'=> 0, 'max'=> 5,
                    'placeholder'=> 'Votre note'
                ]
            ])
            ->add('content', TextareaType::class, [
                'label'=> 'Votre commentaire',
                'attr'=> [
                    'placehorlder' => 'Votre commentaire'
                ]
            ])
            // ->add('product', EntityType::class, [
            //     'class' => Product::class,
            //     'choice_label' => 'id',
            // ])
            // ->add('user', EntityType::class, [
            //     'class' => User::class,
            //     'choice_label' => 'id',
            // ])
            ->add('submit', SubmitType::class, [
                'label' => 'Enregistrer le commentaire',
                'attr' => [
                'class' => 'btn col-12 btn-primary'
                ]
                ]);
        
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Comment::class,
        ]);
    }
}
