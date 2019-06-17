<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Post;

class PostFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // $post = new Post();

        // $post->setName('Post 1');
        // $post->setOwner(1);
        // $post->setSubject(1);
        // $post->setContent('<p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Reprehenderit ipsum excepturi, optio, cupiditate aperiam tenetur temporibus, numquam veritatis incidunt nesciunt impedit ex voluptate. Id voluptatibus quos nobis aperiam recusandae possimus?</p>');
        
        // $manager->persist($post);

        // $manager->flush();
    }
}
