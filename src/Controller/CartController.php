<?php
namespace App\Controller;

use App\Entity\Products;
use App\Entity\Cart;
use App\Repository\CartRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/cart', name: 'cart_')]
class CartController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    
    #[Route('/add-product/{productId}', name: 'add_product', methods: ['POST'])]
    public function addProductToCart(int $productId, UserRepository $userRepository): JsonResponse
    {
        $user = $this->getUser();
    
        if (!$user) {
            return $this->json(['message' => 'User not authenticated'], 401);
        }
    
        $product = $this->entityManager->getRepository(Products::class)->find($productId);
    
        if (!$product) {
            return $this->json(['message' => 'Product not found'], 404);
        }

        $cart = $user->getCart();

        if (!$cart) {
            $cart = new Cart();
            $cart->setUsername($user);
        }

        $cart->addProduct($product);
    
        $this->entityManager->persist($cart);
        $this->entityManager->flush();
    
        return $this->json(['message' => 'Product added to cart']);
    }

    #[Route('/view-cart', name: 'viewcart', methods: ['GET'])]
    public function viewCart(): JsonResponse
    {
        $user = $this->getUser();
    
        if (!$user) {
            return $this->json(['error' => 'User not authenticated'], 401);
        }
    
        $cart = $user->getCart();
    
        if (!$cart) {
            return $this->json(['error' => 'User does not have a cart'], 404);
        }
    
        $productData = [];
    
        $products = $cart->getProducts();
    
        foreach ($products as $product) {
            $productData[] = [
                'id' => $product->getId(),
                'name' => $product->getProductName(),
                'price' => $product->getPrice(),
                'description' => $product->getDescription(),
                'imageUrl' => $product->getImageUrl()
            ];
        }
    
        return $this->json([
            'username' => $user->getFullname(),
            'products' => $productData,
        ]);
    }    

    #[Route('/remove-product/{productId}', name: 'remove_product', methods: ['POST'])]
    public function removeProductFromCart(int $productId): JsonResponse
    {
        $user = $this->getUser();

        if (!$user) {
            return $this->json(['message' => 'User not authenticated'], 401);
        }

        $product = $this->entityManager->getRepository(Products::class)->find($productId);

        if (!$product) {
            return $this->json(['message' => 'Product not found'], 404);
        }

        $cart = $user->getCart();

        if (!$cart) {
            return $this->json(['message' => 'User does not have a cart'], 404);
        }

        $cart->removeProduct($product);

        $this->entityManager->flush();

        return $this->json(['message' => 'Product removed from cart']);
    }
}
