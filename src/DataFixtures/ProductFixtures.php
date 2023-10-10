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

        $product6 = new Products();
        $product6->setProductName('Smartphone');
        $product6->setProductCode('EAP-1001');
        $product6->setReleaseDate('June 10, 2023');
        $product6->setDescription('High-end smartphone with a large OLED display');
        $product6->setPrice(799.99);
        $product6->setStarRating(4.5);
        $product6->setImageUrl('assets/images/smartphone.png');
        $product6->setCategory($this->getReference('category_1'));
        $manager->persist($product6);

        $product7 = new Products();
        $product7->setProductName('Laptop');
        $product7->setProductCode('EAP-2002');
        $product7->setReleaseDate('May 5, 2023');
        $product7->setDescription('Powerful laptop with Intel Core i7 processor');
        $product7->setPrice(1299.99);
        $product7->setStarRating(4.8);
        $product7->setImageUrl('assets/images/laptop.png');
        $product7->setCategory($this->getReference('category_1'));
        $manager->persist($product7);

        $product8 = new Products();
        $product8->setProductName('Wireless Earbuds');
        $product8->setProductCode('EAP-3003');
        $product8->setReleaseDate('July 20, 2023');
        $product8->setDescription('Bluetooth wireless earbuds with noise cancellation');
        $product8->setPrice(149.99);
        $product8->setStarRating(4.6);
        $product8->setImageUrl('assets/images/earbuds.png');
        $product8->setCategory($this->getReference('category_1'));
        $manager->persist($product8);

        $product9 = new Products();
        $product9->setProductName('Screwdriver Set');
        $product9->setProductCode('HT-4001');
        $product9->setReleaseDate('April 8, 2023');
        $product9->setDescription('Set of 6 high-quality screwdrivers with various tips');
        $product9->setPrice(19.99);
        $product9->setStarRating(4.4);
        $product9->setImageUrl('assets/images/screwdriver_set.png');
        $product9->setCategory($this->getReference('category_3'));
        $manager->persist($product9);

        $product10 = new Products();
        $product10->setProductName('Adjustable Wrench');
        $product10->setProductCode('HT-5002');
        $product10->setReleaseDate('March 15, 2023');
        $product10->setDescription('10-inch adjustable wrench for versatile use');
        $product10->setPrice(12.49);
        $product10->setStarRating(4.2);
        $product10->setImageUrl('assets/images/wrench.png');
        $product10->setCategory($this->getReference('category_3'));
        $manager->persist($product10);

        $product11 = new Products();
        $product11->setProductName('Hacksaw');
        $product11->setProductCode('HT-6003');
        $product11->setReleaseDate('February 3, 2023');
        $product11->setDescription('Durable hacksaw with a replaceable blade');
        $product11->setPrice(8.99);
        $product11->setStarRating(4.0);
        $product11->setImageUrl('assets/images/hacksaw.png');
        $product11->setCategory($this->getReference('category_3'));
        $manager->persist($product11);

        $product12 = new Products();
        $product12->setProductName('Chef\'s Knife');
        $product12->setProductCode('KU-7001');
        $product12->setReleaseDate('May 25, 2023');
        $product12->setDescription('High-quality 8-inch chef\'s knife for precision cutting');
        $product12->setPrice(34.99);
        $product12->setStarRating(4.7);
        $product12->setImageUrl('assets/images/chefs_knife.png');
        $product12->setCategory($this->getReference('category_2'));
        $manager->persist($product12);

        $product13 = new Products();
        $product13->setProductName('Non-Stick Frying Pan');
        $product13->setProductCode('KU-8002');
        $product13->setReleaseDate('June 12, 2023');
        $product13->setDescription('12-inch non-stick frying pan for easy cooking');
        $product13->setPrice(29.99);
        $product13->setStarRating(4.3);
        $product13->setImageUrl('assets/images/frying_pan.png');
        $product13->setCategory($this->getReference('category_2'));
        $manager->persist($product13);

        $product14 = new Products();
        $product14->setProductName('Blender');
        $product14->setProductCode('KU-9003');
        $product14->setReleaseDate('April 5, 2023');
        $product14->setDescription('Powerful blender for smoothies and shakes');
        $product14->setPrice(49.99);
        $product14->setStarRating(4.5);
        $product14->setImageUrl('assets/images/blender.png');
        $product14->setCategory($this->getReference('category_2'));
        $manager->persist($product14);

        $product15 = new Products();
        $product15->setProductName('Cutting Board Set');
        $product15->setProductCode('KU-10004');
        $product15->setReleaseDate('July 30, 2023');
        $product15->setDescription('Set of 3 durable cutting boards in different sizes');
        $product15->setPrice(18.99);
        $product15->setStarRating(4.1);
        $product15->setImageUrl('assets/images/cutting_board.png');
        $product15->setCategory($this->getReference('category_2'));
        $manager->persist($product15);

        $product16 = new Products();
        $product16->setProductName('Tennis Racket');
        $product16->setProductCode('SP-1001');
        $product16->setReleaseDate('August 15, 2023');
        $product16->setDescription('Professional-grade tennis racket for improved performance');
        $product16->setPrice(89.99);
        $product16->setStarRating(4.6);
        $product16->setImageUrl('assets/images/tennis_racket.png');
        $product16->setCategory($this->getReference('category_4'));
        $manager->persist($product16);

        $product17 = new Products();
        $product17->setProductName('Mountain Bike');
        $product17->setProductCode('SP-2002');
        $product17->setReleaseDate('July 25, 2023');
        $product17->setDescription('Durable mountain bike with front suspension');
        $product17->setPrice(499.99);
        $product17->setStarRating(4.8);
        $product17->setImageUrl('assets/images/mountain_bike.png');
        $product17->setCategory($this->getReference('category_4'));
        $manager->persist($product17);

        $product18 = new Products();
        $product18->setProductName('Soccer Ball');
        $product18->setProductCode('SP-3003');
        $product18->setReleaseDate('June 8, 2023');
        $product18->setDescription('Official size and weight soccer ball');
        $product18->setPrice(24.99);
        $product18->setStarRating(4.4);
        $product18->setImageUrl('assets/images/soccer_ball.png');
        $product18->setCategory($this->getReference('category_4'));
        $manager->persist($product18);

        $product19 = new Products();
        $product19->setProductName('Yoga Mat');
        $product19->setProductCode('SP-4004');
        $product19->setReleaseDate('May 12, 2023');
        $product19->setDescription('Non-slip yoga mat for comfortable workouts');
        $product19->setPrice(19.95);
        $product19->setStarRating(4.5);
        $product19->setImageUrl('assets/images/yoga_mat.png');
        $product19->setCategory($this->getReference('category_4'));
        $manager->persist($product19);

        $product20 = new Products();
        $product20->setProductName('Basketball Hoop');
        $product20->setProductCode('SP-5005');
        $product20->setReleaseDate('April 3, 2023');
        $product20->setDescription('Adjustable height basketball hoop for home use');
        $product20->setPrice(129.99);
        $product20->setStarRating(4.7);
        $product20->setImageUrl('assets/images/basketball_hoop.png');
        $product20->setCategory($this->getReference('category_4'));
        $manager->persist($product20);

        $product21 = new Products();
        $product21->setProductName('Hiking Backpack');
        $product21->setProductCode('SP-6006');
        $product21->setReleaseDate('February 20, 2023');
        $product21->setDescription('Large capacity hiking backpack with hydration system');
        $product21->setPrice(79.99);
        $product21->setStarRating(4.3);
        $product21->setImageUrl('assets/images/hiking_backpack.png');
        $product21->setCategory($this->getReference('category_4'));
        $manager->persist($product21);

        $product22 = new Products();
        $product22->setProductName('Treadmill');
        $product22->setProductCode('SP-7007');
        $product22->setReleaseDate('March 29, 2023');
        $product22->setDescription('Foldable treadmill for home workouts');
        $product22->setPrice(599.99);
        $product22->setStarRating(4.6);
        $product22->setImageUrl('assets/images/treadmill.png');
        $product22->setCategory($this->getReference('category_4'));
        $manager->persist($product22);

        $product23 = new Products();
        $product23->setProductName('Golf Clubs Set');
        $product23->setProductCode('SP-8008');
        $product23->setReleaseDate('January 10, 2023');
        $product23->setDescription('Complete set of golf clubs for beginners');
        $product23->setPrice(249.99);
        $product23->setStarRating(4.7);
        $product23->setImageUrl('assets/images/golf_clubs.png');
        $product23->setCategory($this->getReference('category_4'));
        $manager->persist($product23);

        $product24 = new Products();
        $product24->setProductName('Camping Tent');
        $product24->setProductCode('SP-9009');
        $product24->setReleaseDate('December 5, 2022');
        $product24->setDescription('4-person waterproof camping tent');
        $product24->setPrice(89.95);
        $product24->setStarRating(4.4);
        $product24->setImageUrl('assets/images/camping_tent.png');
        $product24->setCategory($this->getReference('category_4'));
        $manager->persist($product24);

        $product25 = new Products();
        $product25->setProductName('Dumbbell Set');
        $product25->setProductCode('SP-10010');
        $product25->setReleaseDate('November 18, 2022');
        $product25->setDescription('Adjustable dumbbell set for home fitness');
        $product25->setPrice(149.99);
        $product25->setStarRating(4.5);
        $product25->setImageUrl('assets/images/dumbbell_set.png');
        $product25->setCategory($this->getReference('category_4'));
        $manager->persist($product25);

        $manager->flush();

    }
}