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
use Symfony\Component\HttpFoundation\File\UploadedFile; 

class ProductsControllerTest extends TestCase
{
    public function testGetProducts()
    {
        $productsRepository = $this->createMock(ProductsRepository::class);

        $sampleProducts = [
            new Products(), 
        ];

        $productsRepository->expects($this->once())
            ->method('findAll')
            ->willReturn($sampleProducts);

        $entityManager = $this->createMock(EntityManagerInterface::class);

        $controller = new ProductsController($productsRepository, $entityManager);

        $response = $controller->getProducts();

        $this->assertInstanceOf(JsonResponse::class, $response);

        $content = json_decode($response->getContent(), true);
        $this->assertIsArray($content);
        $this->assertCount(count($sampleProducts), $content);
    }

    public function testGetProduct()
    {
        $id = 22; 

        $product = $this->createMock(Products::class);
        $product->method('getId')->willReturn($id);
        $entityManager = $this->createMock(EntityManagerInterface::class);

        $productsRepository = $this->createMock(ProductsRepository::class);
        $productsRepository->method('find')->willReturn($product);

        $controller = new ProductsController($productsRepository, $entityManager);
        $response = $controller->getProduct($id);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());

        $responseData = json_decode($response->getContent(), true);

        $this->assertArrayHasKey('id', $responseData);

        $this->assertEquals($id, $responseData['id']);
    }

    public function testGetProductNotFound()
    {
        $id = 9999; 

        $productsRepository = $this->createMock(ProductsRepository::class);
        $productsRepository->method('find')->willReturn(null);
        $entityManager = $this->createMock(EntityManagerInterface::class);

        $controller = new ProductsController($productsRepository, $entityManager);
        $response = $controller->getProduct($id);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(404, $response->getStatusCode());

        $responseData = json_decode($response->getContent(), true);

        $this->assertArrayHasKey('error', $responseData);

        $this->assertEquals('Product not found', $responseData['error']);
    }

}
