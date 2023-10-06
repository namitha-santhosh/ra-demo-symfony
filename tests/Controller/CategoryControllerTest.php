<?php

namespace App\Tests\Controller;

use App\Controller\CategoryController;
use App\Entity\Category;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CategoryControllerTest extends TestCase
{
    private CategoryController $controller;
    private EntityManagerInterface $entityManager;
    private ObjectRepository $categoryRepository;

    protected function setUp(): void
    {
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->categoryRepository = $this->createMock(CategoryRepository::class);

        $this->controller = new CategoryController($this->entityManager, $this->categoryRepository);
    }

    public function testGetCategories()
    {

        $sampleCategory = [
            new Category(), 
        ];
        // Mock the findAll method of CategoryRepository to return data
        $this->categoryRepository->expects($this->once())
            ->method('findAll')
            ->willReturn($sampleCategory);

        $response = $this->controller->getCategories($this->categoryRepository);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());

        // Add more assertions as needed based on your expected response
    }

    public function testAddCategory()
    {
        // Mock the persist and flush methods of EntityManager to simulate the persistence process
        $this->entityManager->expects($this->once())
            ->method('persist');
        $this->entityManager->expects($this->once())
            ->method('flush');

        // Mock a request with JSON data
        $data = ['name' => 'New Category'];
         // Encode the JSON data
         $requestData = json_encode($data);

         // Create a Request with JSON data
         $request = Request::create('/api/add-category', 'POST', [], [], [], [], $requestData);
        
         $response = $this->controller->addCategory($request);
 

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());

        // Add more assertions as needed based on your expected response
    }

    // Add more test methods for other controller actions

    protected function tearDown(): void
    {
        // Clean up any resources if necessary
    }
}
