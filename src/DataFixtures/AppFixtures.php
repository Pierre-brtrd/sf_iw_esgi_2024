<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(
        private readonly UserPasswordHasherInterface $hasher
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);

        // Utilisateur Admin
        $user = (new User())
            ->setEmail('admin@test.com')
            ->setFirstName('Admin')
            ->setLastName('User')
            ->setRoles(["ROLE_ADMIN"])
            ->setPassword(
                $this->hasher->hashPassword(new User, 'Test1234!')
            );

        $manager->persist($user);

        // Génération de 10 users random
        for ($i = 1; $i <= 10; $i++) {
            $user = (new User())
                ->setEmail("user_$i@test.com")
                ->setFirstName($i)
                ->setLastName("User")
                ->setPassword(
                    $this->hasher->hashPassword(new User, 'Test1234!')
                );

            $manager->persist($user);
        }

        $manager->flush();
    }
}
