<?php

namespace App\Form;

use App\Entity\Address;
use App\Entity\Carrier;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class OrderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $user = $options['user'];
        $builder
            ->add('addresses', EntityType::class, [
                'label' => 'Choose your delivery address',
                'choice_label' => 'name',
                'required' => true,
                'choices' => $user->getAddresses(),
                'class' => Address::class,
                'multiple' => false,
                'expanded' => true
            ])
            ->add('transporteurs', EntityType::class, [
                'label' => 'Choose your carrier',
                'required' => true,
                'class' => Carrier::class,
                'multiple' => false,
                'expanded' => true
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Confirm my order',
                'attr' => [
                    'class' => 'col-12 btn btn-success btn-lg'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'user'=>[],
        ]);
    }
}