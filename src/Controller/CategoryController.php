<?php

// src/Controller/ProductController.php
namespace App\Controller;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;


class CategoryController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route("/api/category", name: "categories", methods: ['GET'])]
    public function getCategories(CategoryRepository $categoryRepository): JsonResponse{
        $categories = $categoryRepository->findAll();

        if (!$categories) {
            throw $this->createNotFoundException('Category not found');
        }

        $categoryData = [];
        foreach ($categories as $category) {
            $categoryData[] = [
                'id' => $category->getId(),
                'name' => $category->getName(),
            ];
        }

        return new JsonResponse($categoryData);
    }

    #[Route("/api/category/{categoryId}", name: "products_by_category")]
    public function productsByCategory(int $categoryId, CategoryRepository $categoryRepository): JsonResponse
    {
        $category = $categoryRepository->find($categoryId);

        if (!$category) {
            throw $this->createNotFoundException('Category not found');
        }

        $products = $category->getProducts();

        $productsData = [];
        foreach ($products as $product) {
            $productsData[] = [
                'id' => $product->getId(),
                'productName' => $product->getProductName(),
                'productCode' => $product->getProductCode(),
                'price' => $product->getPrice()
            ];
        }

        return new JsonResponse($productsData);

    }

    #[Route("/api/add-category", name:"addcategory", methods:['POST'])]
    public function addCategory(Request $request): JsonResponse{
        $data = json_decode($request->getContent(), true);

        // Validate and process the data as needed
        $category = new Category();
        $category->setName($data['name']);

        // Save the category to the database
        $this->entityManager->persist($category);
        $this->entityManager->flush();

        // Return a success response or handle errors
        return $this->json(['message' => 'Category created successfully'], Response::HTTP_CREATED);
    }
}
