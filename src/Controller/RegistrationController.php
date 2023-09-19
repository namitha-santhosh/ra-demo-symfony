<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\User;
use Symfony\Component\Routing\Annotation\Route; 

class RegistrationController extends AbstractController
{

    #[Route('/api/checkUser', name:'checkUser', methods: ['GET'])]
    public function checkUser(Request $request, EntityManagerInterface $entityManager):JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        // Check if the email already exists
        $userRepository = $entityManager->getRepository(User::class);
        $existingUser = $userRepository->findOneBy(['email' => $data['email']]);

        if ($existingUser) {
            return new JsonResponse(['message' => 'An account with this email already exists. Please log in.'], 400);
        }

         // Return a response indicating that the email is available
        return new JsonResponse(['message' => 'Email is available'], 200);

    }

    #[Route('/api/register', name:'register', methods: ['POST'])]

    public function register(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        // Validate the request data (you may want to use Symfony forms for this)
        $data = json_decode($request->getContent(), true);

       /*  // Check if the email already exists
        $userRepository = $entityManager->getRepository(User::class);
        $existingUser = $userRepository->findOneBy(['email' => $data['email']]);

        if ($existingUser) {
            return new JsonResponse(['message' => 'An account with this email already exists. Please log in.'], 400);
        }
 */
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
