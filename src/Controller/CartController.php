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
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Annotations as OA;

class CartController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    
    //#[Route('api/cart/add-product/{productId}', name: 'add_product', methods: ['POST'])]
    /**
     * @OA\Response(
     *     response=201,
     *     description="Adds a product to the cart .",
     *     @Model(type=Cart::class)
     * ),
     *  @OA\Response(
 *         response=401,
 *         description="User not authenticated",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="error", type="string", example="User not authenticated")
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="User does not have a cart",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="error", type="string", example="User does not have a cart")
 *         )
 *     )
     * @OA\Tag(name="Cart")
     * @Security(name="Bearer")
     * @Route("api/cart/add-product/{productId}", name="add_product", methods={"POST"})
     */ 

    
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
    
        return $this->json(['message' => 'Product added to cart', 'user'=>$user]);
    }

    /**
 * @OA\Get(
 *     path="/api/cart/view-cart",
 *     summary="View user's cart",
 *     tags={"Cart"},
 *     security={{"bearerAuth": {}}},
 *     @OA\Response(
 *         response=200,
 *         description="User's cart details",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="username", type="string", example="John Doe"),
 *             @OA\Property(
 *                 property="products",
 *                 type="array",
 *                 @OA\Items(
 *                     type="object",
 *                     @OA\Property(property="id", type="integer"),
 *                     @OA\Property(property="name", type="string"),
 *                     @OA\Property(property="price", type="number", format="float"),
 *                     @OA\Property(property="description", type="string"),
 *                     @OA\Property(property="imageUrl", type="string")
 *                 )
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="User not authenticated",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="error", type="string", example="User not authenticated")
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="User does not have a cart",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="error", type="string", example="User does not have a cart")
 *         )
 *     )
 * )
 * @Route("/api/cart/view-cart", name="viewcart", methods={"GET"})
 */

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

    #[Route('api/cart/remove-product/{productId}', name: 'remove_product', methods: ['POST'])]
    /**
     * @OA\Response(
     *     response=200,
     *     description="Deletes a product from the cart .",
     *     @Model(type=Cart::class)
     * ),  @OA\Response(
 *         response=401,
 *         description="User not authenticated",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="error", type="string", example="User not authenticated")
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Product not found",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="error", type="string", example="Product not found")
 *         )
 *     )
     * @OA\Tag(name="Cart")
     * @Security(name="Bearer")
     * @Route("/api/cart/remove-product/{productId}", name="remove_product", methods={"POST"})
     */ 
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
