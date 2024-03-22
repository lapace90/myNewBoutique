<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Category;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
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
            $this->setReference('category_1', $category); // Utilisation de setReference() au lieu de addReference()
        }

        $manager->flush();
    }
}
