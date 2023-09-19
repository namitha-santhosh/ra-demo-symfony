<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface; // Import the new interface
use App\Entity\User;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Doctrine\ORM\EntityManagerInterface;

class AuthenticationController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/api/login", name="login", methods={"POST"})
     */
    public function login(Request $request, UserPasswordHasherInterface $passwordHasher, TokenStorageInterface $tokenStorage): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $userRepository = $this->entityManager->getRepository(User::class);
        $user = $userRepository->findOneBy(['email' => $data['email']]);

        if (!$user) {
            return new JsonResponse(['message' => 'User not found'], 404);
        }

        if (!$passwordHasher->isPasswordValid($user, $data['password'])) {
            return new JsonResponse(['message' => 'Invalid password'], 401);
        }

        $password = $data['password']; // Get the password from the request data
        $roles = $user->getRoles(); // Convert the single role to an array
        $token = new UsernamePasswordToken($user, $password, $roles);
        $tokenStorage->setToken($token);

        return new JsonResponse(['message' => 'Login successful'], 200);
    }


    /**
     * @Route("/api/logout", name="logout", methods={"POST"})
     */
    public function logout(): JsonResponse
    {
        // Symfony's security system will automatically handle logout, so you don't need to implement a logout action.
        return new JsonResponse(['message' => 'Logged out'], 200);
    }
}
