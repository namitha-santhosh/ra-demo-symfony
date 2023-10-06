<?php

namespace App\Tests\Entity;

use App\Entity\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function testUserEntity()
    {
        $user = new User();

        $email = 'test@example.com';
        $user->setEmail($email);
        $this->assertEquals($email, $user->getEmail());

        $roles = ['ROLE_USER'];
        $user->setRoles($roles);
        $this->assertEquals($roles, $user->getRoles());

        $password = 'password123';
        $user->setPassword($password);
        $this->assertEquals($password, $user->getPassword());

        $this->assertEquals($email, $user->getUserIdentifier());

        $fullName = 'John Doe';
        $user->setFullname($fullName);
        $this->assertEquals($fullName, $user->getFullname());

        $contact = '1234567890';
        $user->setContact($contact);
        $this->assertEquals($contact, $user->getContact());

        $cart = new \App\Entity\Cart(); 
        $user->setCart($cart);
        $this->assertEquals($cart, $user->getCart());
    }
}
