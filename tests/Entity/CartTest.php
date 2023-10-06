<?php

namespace App\Tests\Entity;

use App\Entity\Cart;
use App\Entity\User;
use App\Entity\CartItem;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;

class CartTest extends TestCase
{
    public function testCartEntity()
    {
        $cart = new Cart();
    
        $user = new User(); 
        $cart->setUsername($user);
        $this->assertEquals($user, $cart->getUsername());
    
        $cartItem1 = new CartItem(); 
        $cartItem2 = new CartItem(); 
    
        $product1 = new \App\Entity\Products();
        $product1->setProductName('Product Name 1');
    
        $product2 = new \App\Entity\Products(); 
        $product2->setProductName('Product Name 2');
    
        $cartItem1->setProducts($product1);
        $cartItem1->setQuantity(2);
    
        $cartItem2->setProducts($product2);
        $cartItem2->setQuantity(3);
    
        $cart->addCartItem($cartItem1);
        $this->assertTrue($cart->getCartItems()->contains($cartItem1));
    
        $cart->addCartItem($cartItem2);
        $this->assertTrue($cart->getCartItems()->contains($cartItem2));
    
        $cart->removeCartItem($cartItem1);
        $this->assertFalse($cart->getCartItems()->contains($cartItem1));
    
        $formattedItems = $cart->getFormattedCartItems();
        $this->assertEquals('Product Name 2 (Quantity: 3)', $formattedItems);
    }
    
}
