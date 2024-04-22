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
                'label' => 'Name',
            ])
            ->add('minPrice', NumberType::class, [
                'required' => false,
                'label' => 'Min Price',
            ])

            ->add('maxPrice', NumberType::class, [
                'required' => false,
                'label' => 'Max Price',
            ])

            ->add('categories', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'name',
                'required' => false,
                'multiple' => true,
                'expanded' => true // choix de plusieurs valeurs
            ])

            ->add('submit', SubmitType::class, ['label' => 'Enter', 'attr' => ['class' => 'btn btn-success col-8 d-block mx-auto']]);;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SearchFilters::class,
          
        ]);
    }
}
