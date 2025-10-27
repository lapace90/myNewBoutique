<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints as Assert;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Full Name',
                'attr' => [
                    'placeholder' => 'Your full name',
                    'class' => 'form-control form-control-lg'
                ],
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Please enter your name'
                    ]),
                    new Assert\Length([
                        'min' => 2,
                        'minMessage' => 'Your name must be at least {{ limit }} characters'
                    ])
                ]
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email Address',
                'attr' => [
                    'placeholder' => 'your.email@example.com',
                    'class' => 'form-control form-control-lg'
                ],
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Please enter your email'
                    ]),
                    new Assert\Email([
                        'message' => 'Please enter a valid email address'
                    ])
                ]
            ])
            ->add('subject', ChoiceType::class, [
                'label' => 'Subject',
                'attr' => [
                    'class' => 'form-control form-control-lg'
                ],
                'placeholder' => 'Select a subject...',
                'choices' => [
                    'General Inquiry' => 'general',
                    'Order Issue' => 'order',
                    'Product Question' => 'product',
                    'Shipping & Delivery' => 'shipping',
                    'Returns & Refunds' => 'returns',
                    'Payment Issue' => 'payment',
                    'Technical Support' => 'technical',
                    'Partnership & Collaboration' => 'partnership',
                    'Other' => 'other'
                ],
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Please select a subject'
                    ])
                ]
            ])
            ->add('message', TextareaType::class, [
                'label' => 'Message',
                'attr' => [
                    'placeholder' => 'Write your message here...',
                    'class' => 'form-control form-control-lg',
                    'rows' => 6
                ],
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Please enter your message'
                    ]),
                    new Assert\Length([
                        'min' => 10,
                        'minMessage' => 'Your message must be at least {{ limit }} characters'
                    ])
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Send Message',
                'attr' => [
                    'class' => 'btn btn-primary btn-lg w-100'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([]);
    }
}