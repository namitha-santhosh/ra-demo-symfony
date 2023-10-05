<?php

namespace Api\Tests\Controller;

use App\Entity\Category;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Controller\ProductsController;
use App\Repository\ProductsRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Products;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\UploadedFile; // Import the Products entity

class ProductsControllerTest extends TestCase
{
    public function testGetProducts()
    {
        // Create a mock for ProductsRepository
        $productsRepository = $this->createMock(ProductsRepository::class);

        // Create sample products data
        $sampleProducts = [
            new Products(), // You can add more sample products here
        ];

        // Mock the findAll method of ProductsRepository to return the sample products
        $productsRepository->expects($this->once())
            ->method('findAll')
            ->willReturn($sampleProducts);

        // Create a mock for EntityManagerInterface
        $entityManager = $this->createMock(EntityManagerInterface::class);

        // Create an instance of the ProductsController with the mocked repository and entityManager
        $controller = new ProductsController($productsRepository, $entityManager);

        // Call the getProducts method
        $response = $controller->getProducts();

        // Assert that the response is an instance of JsonResponse
        $this->assertInstanceOf(JsonResponse::class, $response);

        // You can also assert the content of the JsonResponse if needed
        $content = json_decode($response->getContent(), true);
        $this->assertIsArray($content);
        $this->assertCount(count($sampleProducts), $content);
    }

    public function testGetProduct()
    {
        // Create a mock for ProductsRepository
        $productsRepository = $this->createMock(ProductsRepository::class);

        // Create a sample product
        $sampleProduct = new Products();
        $sampleProduct->setProductName('Sample Product');
        // Set other properties as needed

        // Mock the find method of ProductsRepository to return the sample product
        $productsRepository->expects($this->once())
            ->method('find')
            ->with(1) // The product ID you want to test
            ->willReturn($sampleProduct);

        // Create a mock for EntityManagerInterface
        $entityManager = $this->createMock(EntityManagerInterface::class);

        // Create an instance of the ProductsController with the mocked repository and entityManager
        $controller = new ProductsController($productsRepository, $entityManager);

        // Call the getProduct method with a sample product ID (1)
        $response = $controller->getProduct(1);

        // Assert that the response is an instance of JsonResponse
        $this->assertInstanceOf(JsonResponse::class, $response);

        // You can also assert the content of the JsonResponse if needed
        $content = json_decode($response->getContent(), true);
        $this->assertIsArray($content);
        $this->assertArrayHasKey('productName', $content);
        $this->assertEquals($sampleProduct->getProductName(), $content['productName']);
        // Assert other properties as needed
    }


}
