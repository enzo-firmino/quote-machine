<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategoryFixtures extends Fixture
{
    public const KAAMELOTT = 'category-kaamelott';

    public function load(ObjectManager $manager)
    {
        $kaamelott = new Category();
        $kaamelott->setName('Kaamelott');
        $manager->persist($kaamelott);

        $manager->flush();

        $this->addReference(self::KAAMELOTT, $kaamelott);
    }
}
