<?php
namespace App\Controller;

use App\Entity\Products;
use App\Entity\Cart;
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
        // Get the authenticated user
        $user = $this->getUser();
    
        if (!$user) {
            return $this->json(['message' => 'User not authenticated'], 401);
        }
    
        // Fetch the product from the database
        $product = $this->entityManager->getRepository(Products::class)->find($productId);
    
        if (!$product) {
            return $this->json(['message' => 'Product not found'], 404);
        }
    // Check if the user already has a cart
    $cart = $user->getCart();

    if (!$cart) {
        // If the user doesn't have a cart, create a new one
        $cart = new Cart();
        $cart->setUsername($user);
    }

    // Add the product to the cart
    $cart->addProduct($product);
    
        // Persist the changes to the database
        $this->entityManager->persist($cart);
        $this->entityManager->flush();
    
        // Return a JSON response indicating success
        return $this->json(['message' => 'Product added to cart']);
    }
}
