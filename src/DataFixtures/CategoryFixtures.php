<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Category;

class CategoryFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $category = new Category();
        $category->setName('Electronics');
        $manager->persist($category);

        $category2 = new Category();
        $category2->setName('Kitchen Utensils');
        $manager-> persist($category2);

        $category3 = new Category();
        $category3->setName('Hand tools');
        $manager-> persist($category3);

        $category4 = new Category();
        $category4->setName('Sporting Equipment');
        $manager->persist($category4);

        $manager->flush();

        $this->addReference('category_1', $category);
        $this->addReference('category_2', $category2);
        $this->addReference('category_3', $category3);
        $this->addReference('category_4', $category4);
    }

}
