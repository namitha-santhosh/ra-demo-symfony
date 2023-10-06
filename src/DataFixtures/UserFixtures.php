<?php

namespace App\DataFixtures;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\User;

class UserFixtures extends Fixture{
    public function load(ObjectManager $manager): void{
    $user = new User();
    $user->setEmail('admin@gmail.com');
    $hashedPassword = password_hash('admin123', PASSWORD_BCRYPT);
        $user->setPassword($hashedPassword);
    $user->setRoles(['ROLE_ADMIN']);
    $manager->persist($user);
    $manager->flush();
    }
}