<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Role;

class RoleFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // $product = new Role();
        // $product->setName('ROLE_MODO');
        // $manager->persist($product);

        // $manager->flush();
    }
}
