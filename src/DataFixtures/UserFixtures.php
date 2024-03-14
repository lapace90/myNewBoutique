<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use Doctrine\Persistence\ObjectManager;;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    private $passwordHasher;
    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }
    public function load(ObjectManager $manager)
    {
        $admin = new User();
        $admin->setFirstName('ilaria')
            ->setLastName('pace')
            ->setEmail('admin@test.fr')
            ->setRoles(["ROLE_ADMIN"]);
        $admin->setPassword($this->passwordHasher->hashPassword(
            $admin,
            'Password1'
        ));
        $manager->persist($admin);
        $faker = Factory::create('fr_FR');
        //dump($faker->name);
        //dump($faker->address);
        for ($i = 1; $i < 30; $i++) {
            $user = new User();
            $user->setFirstName($faker->firstName());
            $user->setLastName($faker->lastName());
            $user->setEmail($faker->email());
            $user->setPassword($this->passwordHasher->hashPassword(
                $user,
                'Password1'
            ));
            $manager->persist($user);
        }
        $manager->flush();
    }
}
