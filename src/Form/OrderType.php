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
            //->add('field_name')
    // affichage des adresses de livraison
            ->add('addresses', EntityType::class, [ // addresses n'est pas une propriété existante car pas d'entité rattachée ( voir
                //fichier createForm)
                'label' => 'Choisissez votre adresse de livraison',
                'choice_label' => 'name',
                'required' => true,
                'choices' => $user->getAddresses(),
                'class' => Address::class, // avec quelle classe faire le lien pour le formulaire ( chercher les propriétés à afficher dans
                //le formulaire)
                'multiple' => false,
                'expanded' => true // on veut des radio bouton
            ])
                ->add('transporteurs', EntityType::class, [ // transporteurs n'est pas une propriété existante car pas d'entité rattachée ( voir fichier creatForm)
                    'label' => 'Choisissez votre transporteur',
                    'required' => true,
                    'class' => Carrier::class, // avec quelle classe faire le lien pour le formulaire ( chercher les propriétés à afficher dans le formulaire)
                    'multiple' => false,
                    'expanded' => true // on veut des radio bouton
                ])
                
                ->add('submit', SubmitType::class, [
                    'label' => 'Valider ma commande',
                    'attr' => [
                    'class' => 'col-12 btn btn-success'
                    ]
                    ]);
        }

        public function configureOptions(OptionsResolver $resolver)
        {
        $resolver->setDefaults([
        // Configure your form options here
        // pas de data_class car pas d'entité relié ( on a un formulaire global)
        'user'=>[],
        ]);
        }

}
