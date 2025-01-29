<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\User;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Twig\Environment;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Annotations as OA;

class RegistrationController extends AbstractController
{
    #[Route('/api/register', name: 'register', methods: ['POST'])]
    
    public function register(
        Request $request,
        EntityManagerInterface $entityManager,
        MailerInterface $mailer,
        Environment $twig
    ): JsonResponse {
        $data = json_decode($request->getContent(), true);

        $existingUser = $entityManager->getRepository(User::class)->findOneBy(['email' => $data['email']]);

        if ($existingUser) {
            return new JsonResponse(['error' => 'Email already registered'], Response::HTTP_CONFLICT);
        }

        $user = new User();
        $user->setFullName($data['fullname']);
        $user->setEmail($data['email']);

        $hashedPassword = password_hash($data['password'], PASSWORD_BCRYPT);
        $user->setPassword($hashedPassword);

        $entityManager->persist($user);
        $entityManager->flush();

        // $this->sendRegistrationEmail($user, $mailer, $twig);

        return new JsonResponse(['message' => 'Sign Up Successful'], 201);
    }

    private function sendRegistrationEmail(User $user, MailerInterface $mailer, Environment $twig)
    {
        $email = (new Email())
            ->from('quantumreleasep10@gmail.com')
            ->to($user->getEmail())
            ->subject('QuantumRelease Registration Confirmation')
            ->html($twig->render('emails/registration.html.twig'));

        $mailer->send($email);
    }
}
