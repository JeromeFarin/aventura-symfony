<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Subject;

class SubjectFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // $subject = new Subject();

        // $subject->setName('Subject 1.2');
        // $subject->setSubject(1);
        // $subject->setCategory(0);
        // $subject->setLevel(1);

        // $manager->persist($subject);

        // $manager->flush();
    }
}
