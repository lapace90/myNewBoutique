<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class ResetPasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // ->add('email')
            // ->add('roles')
            // ->add('password')
            // ->add('firstName')
            // ->add('lastName')
            ->add('newPassword', PasswordType::class, ['label' => "New password"])
            ->add('confirmNewPassword', PasswordType::class, ['label' => "Confirm new password"])
            ->add('submit', SubmitType::class, ['label' => 'Edit password']);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
