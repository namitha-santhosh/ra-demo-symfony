<?php

namespace App\Tests\Entity;

use App\Entity\CartItem;
use App\Entity\Cart;
use App\Entity\Products;
use PHPUnit\Framework\TestCase;

class CartItemTest extends TestCase
{
    public function testCartItemEntity()
    {
        $cartItem = new CartItem();

        $cart = new Cart(); 
        $product = new Products(); 

        $cartItem->setCart($cart);
        $this->assertEquals($cart, $cartItem->getCart());

        $cartItem->setProducts($product);
        $this->assertEquals($product, $cartItem->getProducts());

        $quantity = 3;
        $cartItem->setQuantity($quantity);
        $this->assertEquals($quantity, $cartItem->getQuantity());

        $cartItem->setCart($cart);
        $cart->addCartItem($cartItem);

        $cartItem->preRemove();
        $this->assertNull($cartItem->getCart());
        $this->assertFalse($cart->getCartItems()->contains($cartItem));
    }
}
