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

    $user2 = new User();
    $user2->setEmail('namitha@gmail.com');
    $hashedPassword = password_hash('hello', PASSWORD_BCRYPT);
        $user2->setPassword($hashedPassword);
    $user2->setRoles(['ROLE_USER']);
    $manager->persist($user2);
    $manager->flush();
    }
}