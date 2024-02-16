<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class ChangePasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', TextType::class, ['label'=> 'My E-mail', 'disabled'=>true])
            //->add('roles')
            //->add('password')
            ->add('firstName', TextType::class, ['label'=> 'My Name', 'disabled'=>true])
            ->add('lastName', TextType::class, ['label'=> 'My Last Name', 'disabled'=>true])
            //->add('birthDay')
            ->add('oldPassword', PasswordType::class, ['required' => true, 'label' => false, 'attr' => ['placeholder' => 'Enter your old Password, please.']])
            ->add('newPassword', PasswordType::class, ['required' => true, 'label' => false, 'attr' => ['placeholder' => 'Enter your new Password, please.']])
            ->add('confirmNewPassword', PasswordType::class, ['required' => true, 'label' => false, 'attr' => ['placeholder' => 'Confirm your new Password']])
            ->add('submit', SubmitType::class, ['label' => 'Sign In', 'attr' => ['class' => 'btn btn-success col-8 d-block mx-auto']]);

    
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
