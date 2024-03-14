<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Product;
use App\Entity\Category;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');
        // création des catégories
        for ($i = 1; $i < 11; $i++) {
            $categorie = new Category();
            $categorie->setName('CAT' . $i);
            $categorie->setDescription($faker->paragraph(2));
            $manager->persist($categorie);

            // création des produits
            for ($k = 1; $k < 5; $k++) {

                $name = $faker->word(3, true);
                // on récupère l'objet catégorie (qui ont été crées
                $product = new Product();
                $product->setCategory($categorie);
                $product->setName($name);
                $product->setDescription($faker->paragraph(2));
                $product->setPrice($faker->numberBetween(100, 2000));
                $product->setSubtitle($faker->word(3, true));
                //$product->setSlug($name . $k);
                // images sous forme de lien
                // $product->setIllustration($faker->imageUrl(360, 360, 'PRODUCTS'));
                // images en dur
                $product->setPicture('https://picsum.photos/360?random=' . $i);
                $manager->persist($product);
            }
        }
        $manager->flush();
    }
}
