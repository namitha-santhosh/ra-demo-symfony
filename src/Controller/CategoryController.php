<?php

// src/Controller/ProductController.php
namespace App\Controller;

use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

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
}
