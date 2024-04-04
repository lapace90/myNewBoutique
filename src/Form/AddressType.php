<?php

namespace App\Form;

use Faker\Provider\fr_FR\Address;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;

class AddressType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Address Name',
                'required' => false,
                'attr' => [
                    'placeHolder' => "Name your address"
                ]
            ])
            ->add('firstName', TextType::class, [
                'label' => 'First Name',
                'required' => false,
                'attr' => [
                    'placeHolder' => "Enter your name"
                ]
            ])
            ->add('lastName', TextType::class, [
                'label' => 'Last Name',
                'required' => false,
                'attr' => [
                    'placeHolder' => "Enter your last name"
                ]
            ])
            ->add('company', TextType::class, [
                'label' => 'Your Company',
                'required' => false,
                'attr' => [
                    'placeHolder' => "(facultatif) Enter your company's name"
                ]
            ])
            ->add('address', TextType::class, [
                'label' => 'Your Address',
                'required' => false,
                'attr' => [
                    'placeHolder' => "8 rue des lilas ..."
                ]
            ])
            ->add('postal', TextType::class, [
                'label' => 'Your Zipcode',
                'required' => false,
                'attr' => [
                    'placeHolder' => "Enter your zipcode"
                ]
            ])
            ->add('city', TextType::class, [
                'label' => 'City',
                'required' => false,
                'attr' => [
                    'placeHolder' => "Enter your city"
                ]
            ])
            ->add('country', CountryType::class, [
                'label' => 'Country',
                // 'preferred_choice' => ['FR'],
                'attr' => [
                    'placeHolder' => "Enter your country"
                ]
            ])
            ->add('phone', TelType::class, [
                'label' => 'Your Phone',
                'required' => false,
                'attr' => [
                    'placeHolder' => "Enter your phone"
                ]
            ])
            
            ->add('submit', SubmitType::class, [
                'label' => 'Save',
                'attr' => [
                    'class' => "btn btn-success col-12"
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
            // 'data_class' => Address::class,
        ]);
    }
}
