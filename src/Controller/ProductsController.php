<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Products;
use App\Entity\Category;
use App\Repository\ProductsRepository;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Asset\Context\NullContext;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\UploadedFile;


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
    public function getProducts(Request $request): JsonResponse
    {
        $token = $request->headers->get('Authorization');
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
    public function createProduct(Request $request, LoggerInterface $logger): JsonResponse
    {
        $baseUrl = 'http://localhost:8000'; // Replace with your Symfony server's base URL

        // Handle form data
        $productName = $request->request->get('productName');
        $productCode = $request->request->get('productCode');
        $releaseDate = $request->request->get('releaseDate');
        $description = $request->request->get('description');
        $price = $request->request->get('price');
        $starRating = $request->request->get('starRating');
        $categoryName = $request->request->get('categoryName');
        $category = $this->entityManager->getRepository(Category::class)->findOneBy(['name' => $categoryName]);

        if (!$category) {
            return new JsonResponse(['message' => 'Category not found'], JsonResponse::HTTP_NOT_FOUND);
        }

        // Handle image upload
        $uploadedImage = $request->files->get('image');
        
        if (!$uploadedImage) {
            return new JsonResponse(['message' => 'File upload failed'], JsonResponse::HTTP_NOT_FOUND);
        }

        if ($uploadedImage instanceof UploadedFile) {
            $uploadsDirectory = $this->getParameter('kernel.project_dir') . '/public/uploads';
            $imageFileName = md5(uniqid()) . '.' . $uploadedImage->guessExtension();
            $uploadedImage->move($uploadsDirectory, $imageFileName);
            $imageUrl = '/uploads/' . $imageFileName;
            $imageUrl = $baseUrl . '/uploads/' . $imageFileName;
        } else {
            $imageUrl = null;
        }

        // Create and persist the product entity
        $product = new Products();
        $product->setProductName($productName);
        $product->setProductCode($productCode);
        $product->setReleaseDate($releaseDate);
        $product->setDescription($description);
        $product->setPrice($price);
        $product->setStarRating($starRating);
        $product->setImageUrl($imageUrl);
        $product->setCategory($category);

        $this->entityManager->persist($product);
        $this->entityManager->flush();

        $logger->info('Product Created Successfully');

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

    #[Route('/api/products/imgedit/{id}', name: 'update_product_with_image', methods: ['PUT'])]
    public function updateProductWithImage($id, Request $request): JsonResponse
    {
        $product = $this->productsRepository->find($id);

        if (!$product) {
            return new JsonResponse(['error' => 'Product not found'], Response::HTTP_NOT_FOUND);
        }

        // Handle form data
        $productName = $request->request->get('productName');
        $productCode = $request->request->get('productCode');
        $releaseDate = $request->request->get('releaseDate');
        $description = $request->request->get('description');
        $price = $request->request->get('price');
        $starRating = $request->request->get('starRating');

        $imageUrl = $product->getImageUrl(); // Initialize imageUrl as the current image URL

        // Handle image upload
        $uploadedImage = $request->files->get('image');
        

        if ($uploadedImage instanceof UploadedFile) {
            $uploadsDirectory = $this->getParameter('kernel.project_dir') . '/public/uploads';
            $imageFileName = md5(uniqid()) . '.' . $uploadedImage->guessExtension();
            $uploadedImage->move($uploadsDirectory, $imageFileName);
            $imageUrl = '/uploads/' . $imageFileName;
        }

        // Update the product entity if form data is provided
        if ($productName !== null) {
            $product->setProductName($productName);
        }
        if ($productCode !== null) {
            $product->setProductCode($productCode);
        }
        if ($releaseDate !== null) {
            $product->setReleaseDate($releaseDate);
        }
        if ($description !== null) {
            $product->setDescription($description);
        }
        if ($price !== null) {
            $product->setPrice($price);
        }
        if ($starRating !== null) {
            $product->setStarRating($starRating);
        }
        $product->setImageUrl($imageUrl);

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
