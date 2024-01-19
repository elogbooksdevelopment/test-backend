<?php

namespace App\DataFixtures;

use App\Entity\PropertyClass;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $entity = new PropertyClass();
        $entity->setName('Elogs House Property');
        $manager->persist($entity);
        $manager->flush();
    }
}
