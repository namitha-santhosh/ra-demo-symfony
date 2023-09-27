<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\User;
use Symfony\Component\Routing\Annotation\Route; 
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;


class RegistrationController extends AbstractController
{

    #[Route('/api/register', name:'register', methods: ['POST'])]

    public function register(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        // Validate the request data (you may want to use Symfony forms for this)
        $data = json_decode($request->getContent(), true);

        // Create a new user
        $user = new User();
        $user->setFullName($data['fullname']);
        $user->setEmail($data['email']);
        $user->setContact($data['contact']);
        // Set other user properties

        // Hash and set the user's password (you should hash it for security)
        $hashedPassword = password_hash($data['password'], PASSWORD_BCRYPT);
        $user->setPassword($hashedPassword);

        $entityManager->persist($user);
        $entityManager->flush();

        return new JsonResponse(['message' => 'Sign Up Successful'], 201);
    }

    
}
