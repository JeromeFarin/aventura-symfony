<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Comment;

class CommentFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // $comment = new Comment();

        // $comment->setPost(1);
        // $comment->setContent('<p>I am a comment of this post</p>');

        // $manager->persist($comment);

        // $manager->flush();
    }
}
