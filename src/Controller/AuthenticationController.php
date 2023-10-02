<?php

namespace App\Controller;

use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface; 
use App\Entity\User;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Doctrine\ORM\EntityManagerInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Annotations as OA;

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
     * @OA\Response(
     *     response=200,
     *     description="Login Successful",
     *     @Model(type=User::class)
     * ),
     * @OA\Response(
     *     response=404,
     *     description="User not found."
     * ),
     * @OA\Response(
     *     response=401,
     *     description="Invalid password"
     * )
     * @OA\Tag(name="Login")
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


        $token = $this->jwtTokenManager->create($user);

        $response = new JsonResponse(['message' => 'Login successful', 'fullName' => $fullName]);
        $response->headers->set('Authorization', 'Bearer ' . $token);

        return $response;

        //return new JsonResponse(['message' => 'Login successful', 'fullName' => $fullName], 200);
    }


    /**
     * @OA\Response(
     *     response=200,
     *     description="Logged Out",
     *     @Model(type=User::class)
     * )
     * @OA\Tag(name="Logout")
     * @Route("/api/logout", name="logout", methods={"POST"})
     */
    public function logout(): JsonResponse
    {
        // Symfony's security system will automatically handle logout, so you don't need to implement a logout action.
        return new JsonResponse(['message' => 'Logged out'], 200);
    }
}
