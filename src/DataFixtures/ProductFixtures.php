<?php

namespace App\DataFixtures;

use App\Entity\Products;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ProductFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $product = new Products();
        $product->setProductName('Leaf Rake');
        $product->setProductCode('GDN-0011');
        $product->setReleaseDate('March 19,2021');
        $product->setDescription('Leaf rake with 48-inch wooden handle');
        $product->setPrice(19.95);
        $product->setstarRating(3.2);
        $product->setImageUrl("assets/images/leaf_rake.png");
        $product->setCategory($this->getReference('category_3'));

        $manager->persist($product);

        $product2 = new Products();
        $product2->setProductName('Garden Cart');
        $product2->setProductCode('GDN-0023');
        $product2->setReleaseDate('March 18,2021');
        $product2->setDescription('15 gallon capacity rolling garden cart');
        $product2->setPrice(32.99);
        $product2->setstarRating(4.2);
        $product2->setImageUrl("assets/images/garden_cart.png");
        $product2->setCategory($this->getReference('category_3'));
        $manager->persist($product2);

        $product3 = new Products();
        $product3->setProductName('Hammer');
        $product3->setProductCode('TBX-0048');
        $product3->setReleaseDate('March 21,2021');
        $product3->setDescription('Curved claw steel hammer');
        $product3->setPrice(8.9);
        $product3->setstarRating(4.8);
        $product3->setImageUrl("assets/images/hammer.png");
        $product3->setCategory($this->getReference('category_3'));
        $manager->persist($product3);

        $product4 = new Products();
        $product4->setProductName('Saw');
        $product4->setProductCode('TBX-0022');
        $product4->setReleaseDate('March 15,2021');
        $product4->setDescription('15-inch steel blade hand saw');
        $product4->setPrice(11.55);
        $product4->setstarRating(3.7);
        $product4->setImageUrl("assets/images/saw.png");
        $product4->setCategory($this->getReference('category_3'));
        $manager->persist($product4);

        $product5= new Products();
        $product5->setProductName('Video Game Controller');
        $product5->setProductCode('GMG-0042');
        $product5->setReleaseDate('October 15,2020');
        $product5->setDescription('Standard two-button video game controller');
        $product5->setPrice(35.95);
        $product5->setstarRating(4.6);
        $product5->setImageUrl("assets/images/xbox-controller.png");
        $product5->setCategory($this->getReference('category_1'));
        $manager->persist($product5);

        $manager->flush();


    }
}