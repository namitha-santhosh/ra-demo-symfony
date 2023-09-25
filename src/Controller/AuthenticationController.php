<?php

namespace App\Controller;

use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
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
    private $jwtTokenManager;

    public function __construct(EntityManagerInterface $entityManager, JWTTokenManagerInterface $jwtTokenManager)
    {
        $this->entityManager = $entityManager;
        $this->jwtTokenManager = $jwtTokenManager;
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

        $fullName = $user->getFullName();


        // Generate a JWT token for the authenticated user
        $token = $this->jwtTokenManager->create($user);

        // Store the JWT token in the response
        $response = new JsonResponse(['message' => 'Login successful', 'fullName' => $fullName]);
        $response->headers->set('Authorization', 'Bearer ' . $token);

        return $response;

        //return new JsonResponse(['message' => 'Login successful', 'fullName' => $fullName], 200);
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
