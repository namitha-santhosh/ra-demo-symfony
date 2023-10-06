<?php

namespace App\Tests\Entity;

use App\Entity\Products;
use App\Entity\Category;
use App\Entity\CartItem;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;

class ProductsTest extends TestCase
{
    public function testProductsEntity()
    {
        $products = new Products();

        $productName = 'Product Name';
        $products->setProductName($productName);
        $this->assertEquals($productName, $products->getProductName());

        $productCode = 'P123';
        $products->setProductCode($productCode);
        $this->assertEquals($productCode, $products->getProductCode());

        $releaseDate = '2023-01-15';
        $products->setReleaseDate($releaseDate);
        $this->assertEquals($releaseDate, $products->getReleaseDate());

        $description = 'Product description';
        $products->setDescription($description);
        $this->assertEquals($description, $products->getDescription());

        $price = 99.99;
        $products->setPrice($price);
        $this->assertEquals($price, $products->getPrice());

        $starRating = 4.5;
        $products->setStarRating($starRating);
        $this->assertEquals($starRating, $products->getStarRating());

        $imageUrl = 'src/assets/img/screwdriver.png';
        $products->setImageUrl($imageUrl);
        $this->assertEquals($imageUrl, $products->getImageUrl());

        $category = new Category(); 
        $products->setCategory($category);
        $this->assertEquals($category, $products->getCategory());

        $cartItem1 = new CartItem(); 
        $cartItem2 = new CartItem(); 

        $products->addCartItem($cartItem1);
        $this->assertTrue($products->getCartItems()->contains($cartItem1));

        $products->addCartItem($cartItem2);
        $this->assertTrue($products->getCartItems()->contains($cartItem2));

        $products->removeCartItem($cartItem1);
        $this->assertFalse($products->getCartItems()->contains($cartItem1));
    }
}
