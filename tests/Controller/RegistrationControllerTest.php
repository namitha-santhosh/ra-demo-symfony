<?php

namespace App\Tests\Controller;

use App\Controller\RegistrationController;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use App\Entity\User;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Twig\Environment;

class RegistrationControllerTest extends TestCase
{
    private RegistrationController $controller;
    private EntityManagerInterface $entityManager;
    private MailerInterface $mailer;
    private Environment $twig;

    protected function setUp(): void
    {
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->mailer = $this->createMock(MailerInterface::class);
        $this->twig = $this->createMock(Environment::class);

        $this->controller = new RegistrationController();
    }

    public function testRegisterSuccess()
    {
        // Mock request data
        $requestData = [
            'fullname' => 'John Doe',
            'email' => 'johndoe@example.com',
            'contact' => '1234567890',
            'password' => 'password123',
        ];

        // Mock the EntityManager to return null (user does not exist)
        $this->entityManager->expects($this->once())
            ->method('getRepository')
            ->willReturnSelf();
        $this->entityManager->expects($this->once())
            ->method('findOneBy')
            ->willReturn(null);

        // Mock the sendRegistrationEmail method
        $this->mailer->expects($this->once())
            ->method('send');
        $this->twig->expects($this->once())
            ->method('render');

        // Create a mock request with JSON data
        $request = Request::create('/api/register', 'POST', [], [], [], [], json_encode($requestData));

        $response = $this->controller->register($request, $this->entityManager, $this->mailer, $this->twig);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());

        // Add more assertions based on your expected response
    }

    public function testRegisterEmailAlreadyExists()
    {
        // Mock request data
        $requestData = [
            'fullname' => 'John Doe',
            'email' => 'johndoe@example.com',
            'contact' => '1234567890',
            'password' => 'password123',
        ];

        // Mock the EntityManager to return an existing user
        $existingUser = $this->createMock(User::class);
        $this->entityManager->expects($this->once())
            ->method('getRepository')
            ->willReturnSelf();
        $this->entityManager->expects($this->once())
            ->method('findOneBy')
            ->willReturn($existingUser);

        // Create a mock request with JSON data
        $request = Request::create('/api/register', 'POST', [], [], [], [], json_encode($requestData));

        $response = $this->controller->register($request, $this->entityManager, $this->mailer, $this->twig);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_CONFLICT, $response->getStatusCode());

        // Add more assertions based on your expected response
    }

    // Add more test methods for edge cases and error scenarios

    protected function tearDown(): void
    {
        // Clean up any resources if necessary
    }
}
