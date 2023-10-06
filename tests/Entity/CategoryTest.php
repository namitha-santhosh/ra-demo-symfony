<?php

namespace App\Tests\Entity;

use App\Entity\Category;
use App\Entity\Products;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;

class CategoryTest extends TestCase
{
    public function testCategoryEntity()
    {
        $category = new Category();
        $categoryName = 'Category Name';
        $category->setName($categoryName);
        $this->assertEquals($categoryName, $category->getName());

        $product1 = new Products(); 
        $product2 = new Products(); 

        $category->addProduct($product1);
        $this->assertTrue($category->getProducts()->contains($product1));

        $category->addProduct($product2);
        $this->assertTrue($category->getProducts()->contains($product2));

        $category->removeProduct($product1);
        $this->assertFalse($category->getProducts()->contains($product1));
    }
}
