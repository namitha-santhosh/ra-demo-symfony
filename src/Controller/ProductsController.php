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
use Symfony\Component\String\Slugger\SluggerInterface;


class ProductsController extends AbstractController
{
    private $productsRepository;
    private $logger;
    private $entityManager;
    
    public function __construct(ProductsRepository $productsRepository, EntityManagerInterface $entityManager, LoggerInterface $logger)
    {
        $this->productsRepository = $productsRepository;
        $this->entityManager = $entityManager;
        $this->logger =$logger;
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
        $baseUrl = 'http://localhost:8000'; 

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
        $categoryName = $request->request->get('categoryName');
        $category = $this->entityManager->getRepository(Category::class)->findOneBy(['name' => $categoryName]);
        $product->setCategory($category);


        
        $categoryId = $requestData['categoryId'];

        $category = $this->entityManager->getRepository(Category::class)->find($categoryId);
    
        if (!$category) {
            return new JsonResponse(['error' => 'Category not found'], Response::HTTP_BAD_REQUEST);
        }

        $product->setCategory($category);

        $this->entityManager->flush();

        return new JsonResponse(['message' => 'Product updated successfully'], JsonResponse::HTTP_OK);
    } 

    #[Route('/api/products/imgedit/{id}', name: 'update_product_with_image', methods: ['POST'])]
    public function updateProductWithImage($id, Request $request, SluggerInterface $slugger): JsonResponse
    {
        $product = $this->entityManager->getRepository(Products::class)->find($id);
        $baseUrl = 'http://localhost:8000'; 

        if (!$product) {
            return new JsonResponse(['error' => 'Product not found'], Response::HTTP_NOT_FOUND);
        }

        $productName = $request->request->get('productName');
        $productCode = $request->request->get('productCode');
        $releaseDate = $request->request->get('releaseDate');
        $description = $request->request->get('description');
        $price = $request->request->get('price');
        $starRating = $request->request->get('starRating');
        $categoryName = $request->request->get('categoryName');
        $category = $this->entityManager->getRepository(Category::class)->findOneBy(['name' => $categoryName]);

        $uploadedImage = $request->files->get('image');
        $imageUrl = $product->getImageUrl(); 
        
        if ($uploadedImage instanceof UploadedFile) {
            // Generate a unique file name for the uploaded image
            $originalFilename = pathinfo($uploadedImage->getClientOriginalName(), PATHINFO_FILENAME);
            $safeFilename = $slugger->slug($originalFilename);
            $newFilename = $safeFilename.'-'.uniqid().'.'.$uploadedImage->guessExtension();

            // Move the uploaded image to the desired directory
            $uploadsDirectory = $this->getParameter('kernel.project_dir') . '/public/uploads';
            $uploadedImage->move($uploadsDirectory, $newFilename);

            // Update the image URL
            $imageUrl = '/uploads/' . $newFilename;
            $imageUrl = $baseUrl . '/uploads/' . $newFilename;

        }

      /*   if ($uploadedImage instanceof UploadedFile) {
            $uploadsDirectory = $this->getParameter('kernel.project_dir') . '/public/uploads';
            $imageFileName = md5(uniqid()) . '.' . $uploadedImage->guessExtension();
            $uploadedImage->move($uploadsDirectory, $imageFileName);
            $imageUrl = '/uploads/' . $imageFileName;
            $imageUrl = $baseUrl . '/uploads/' . $imageFileName;
           
        } */

        if ($productName !== null) {
            $product->setProductName($productName);
        }
        else{
           return new JsonResponse(['message' => 'Name is null']);
        }
        if ($productCode !== null) {
            $product->setProductCode($productCode);
        }
        else{
            return new JsonResponse(['message' => 'code is null']);
        }
        if ($releaseDate !== null) {
            $product->setReleaseDate($releaseDate);
        }
        else{
           return new JsonResponse(['message' => 'date is null']);
        }
        if ($description !== null) {
            $product->setDescription($description);
        }
        //else{
        //    return new JsonResponse(['message' => 'desc is null']);
        //}
        if ($price !== null) {
            $product->setPrice($price);
        }
        //else{
        //    return new JsonResponse(['message' => 'price is null']);
        //}
        if ($starRating !== null) {
            $product->setStarRating($starRating);
        }

        if($category !== null){
            $product->setCategory($category);
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
            'category' => $product->getCategory()
        ];
    }
}
