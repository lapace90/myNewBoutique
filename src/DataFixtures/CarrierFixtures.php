<?php

namespace App\DataFixtures;

use App\Entity\Carrier;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class CarrierFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $carriers = [
            [
                'name' => 'Colissimo',
                'description' => 'Livraison standard à domicile sous 48-72h. Suivi de colis inclus.',
                'price' => 490 // 4.90€
            ],
            [
                'name' => 'Chronopost Express',
                'description' => 'Livraison express en 24h avant 13h. Idéal pour les commandes urgentes.',
                'price' => 1290 // 12.90€
            ],
            [
                'name' => 'Mondial Relay',
                'description' => 'Livraison en point relais sous 3-5 jours. Solution économique et flexible.',
                'price' => 350 // 3.50€
            ],
            [
                'name' => 'DHL Express',
                'description' => 'Livraison internationale express sous 2-3 jours. Suivi premium.',
                'price' => 1990 // 19.90€
            ],
            [
                'name' => 'UPS Standard',
                'description' => 'Livraison sécurisée à domicile ou en bureau sous 3-5 jours ouvrés.',
                'price' => 890 // 8.90€
            ],
            [
                'name' => 'Click & Collect',
                'description' => 'Retrait gratuit en magasin sous 2h. Commandez en ligne, retirez rapidement!',
                'price' => 0 // Gratuit
            ]
        ];

        foreach ($carriers as $carrierData) {
            $carrier = new Carrier();
            $carrier->setName($carrierData['name'])
                ->setDescription($carrierData['description'])
                ->setPrice($carrierData['price']);
            
            $manager->persist($carrier);
        }

        $manager->flush();
    }
}