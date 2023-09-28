<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\User;
use Symfony\Component\Routing\Annotation\Route; 
use OpenApi\Annotations as OA;

class RegistrationController extends AbstractController
{

    #[Route('/api/register', name:'register', methods: ['POST'])]

    public function register(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $user = new User();
        $user->setFullName($data['fullname']);
        $user->setEmail($data['email']);
        $user->setContact($data['contact']);

        $hashedPassword = password_hash($data['password'], PASSWORD_BCRYPT);
        $user->setPassword($hashedPassword);

        $entityManager->persist($user);
        $entityManager->flush();

        return new JsonResponse(['message' => 'Sign Up Successful'], 201);
    }

    
}
