<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Category;

class CategoryFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // $category = new Category();
        // $category->setName('Category 2');
        // $category->setLevel(3);
        // $manager->persist($category);

        // $manager->flush();
    }
}
