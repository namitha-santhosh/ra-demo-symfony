<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Products;
use App\Repository\ProductsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductsController extends AbstractController
{
    private $productsRepository;
    private $entityManager;
    
    public function __construct(ProductsRepository $productsRepository, EntityManagerInterface $entityManager)
    {
        $this->productsRepository = $productsRepository;
        $this->entityManager = $entityManager;
    }

    #[Route('/api/products', name: 'products', methods: ['GET'])]
    public function getProducts(): JsonResponse
    {
        $products = $this->productsRepository->findAll();

        $productsArray = [];
        foreach ($products as $product) {
            $productData = $this->serializeProduct($product);
            $productsArray[] = $productData;
        }

        return new JsonResponse($productsArray);
    }

    #[Route('/api/products/{id}', name: 'get_product', methods: ['GET'])]
    public function getProduct($id): JsonResponse
    {
        $product = $this->productsRepository->find($id);

        if (!$product) {
            return new JsonResponse(['error' => 'Product not found'], Response::HTTP_NOT_FOUND);
        }

        $productData = $this->serializeProduct($product);

        return new JsonResponse($productData);
    }

    #[Route('/api/products', name: 'create_product', methods: ['POST'])]
    public function createProduct(Request $request): JsonResponse
    {
        $requestData = json_decode($request->getContent(), true);


        $product = new Products();
        $product->setProductName($requestData['productName']);
        $product->setProductCode($requestData['productCode']);
        $product->setReleaseDate($requestData['releaseDate']);
        $product->setDescription($requestData['description']);
        $product->setPrice($requestData['price']);
        $product->setStarRating($requestData['starRating']);
        $product->setImageUrl($requestData['imageUrl']);

        $this->entityManager->persist($product);
        $this->entityManager->flush();

        return new JsonResponse(['message' => 'Product created successfully'], JsonResponse::HTTP_CREATED);
    }

    #[Route('/api/products/{id}', name: 'update_product', methods: ['PUT'])]
    public function updateProduct($id, Request $request): JsonResponse
    {
        $product = $this->productsRepository->find($id);

        if (!$product) {
            return new JsonResponse(['error' => 'Product not found'], Response::HTTP_NOT_FOUND);
        }

        $requestData = json_decode($request->getContent(), true);

        $product->setProductName($requestData['productName']);
        $product->setProductCode($requestData['productCode']);
        $product->setReleaseDate($requestData['releaseDate']);
        $product->setDescription($requestData['description']);
        $product->setPrice($requestData['price']);
        $product->setStarRating($requestData['starRating']);
        $product->setImageUrl($requestData['imageUrl']);

        $this->entityManager->flush();

        return new JsonResponse(['message' => 'Product updated successfully'], JsonResponse::HTTP_OK);
    }

    #[Route('/api/products/{id}', name: 'delete_product', methods: ['DELETE'])]
    public function deleteProduct($id): JsonResponse
    {
        $product = $this->productsRepository->find($id);

        if (!$product) {
            return new JsonResponse(['error' => 'Product not found'], Response::HTTP_NOT_FOUND);
        }

        
        $this->entityManager->remove($product);
        $this->entityManager->flush();

        return new JsonResponse(['message' => 'Product deleted successfully'], JsonResponse::HTTP_OK);
    }



    private function serializeProduct(Products $product): array
    {
        return [
            'id' => $product->getId(),
            'productName' => $product->getProductName(),
            'productCode' => $product->getProductCode(),
            'releaseDate' => $product->getReleaseDate(), 
            'description' => $product->getDescription(),
            'price' => $product->getPrice(),
            'starRating' => $product->getStarRating(),
            'imageUrl' => $product->getImageUrl(),
        ];
    }
}
