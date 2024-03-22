<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Product;
use App\Entity\Category;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class ProductsFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        // Création des catégories
        for ($i = 1; $i <= 10; $i++) {
            $category = new Category();
            $category->setName('Category ' . $i);
            $category->setDescription($faker->paragraph(2));
            $manager->persist($category);

            // Création des produits pour chaque catégorie
            for ($j = 1; $j <= 5; $j++) {
                $product = new Product();
                $product->setName($faker->words(3, true))
                    ->setDescription($faker->paragraph(3))
                    ->setSubtitle($faker->words(3, true))
                    ->setPrice($faker->numberBetween(1000, 20000))
                    ->setPicture('https://picsum.photos/360?random=' . $i)
                    ->setCategory($category);
                
                $manager->persist($product);
            }
        }

        $manager->flush();
    }
}
