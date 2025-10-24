<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\SearchFilters;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class SearchFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'required' => false,
                'label' => 'Product Name',
                'attr' => [
                    'placeholder' => 'Search by name...',
                    'class' => 'form-control'
                ]
            ])
            ->add('minPrice', NumberType::class, [
                'required' => false,
                'label' => 'Min Price (€)',
                'attr' => [
                    'placeholder' => 'e.g. 10',
                    'class' => 'form-control',
                    'step' => '0.01'
                ]
            ])
            ->add('maxPrice', NumberType::class, [
                'required' => false,
                'label' => 'Max Price (€)',
                'attr' => [
                    'placeholder' => 'e.g. 100',
                    'class' => 'form-control',
                    'step' => '0.01'
                ]
            ])
            ->add('categories', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'name',
                'required' => false,
                'multiple' => true,
                'expanded' => true,
                'label' => 'Categories'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SearchFilters::class,

        ]);
    }
}
