<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Validator\Constraints\DateTime;

class UserFixtures extends Fixture
{
    public const ENZO = 'enzo';

    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setName('Enzo');
        $user->setEmail('firmino.enzo@hotmail.fr');
        $user->setPassword('symfony');
        $user->setDateInscription(new \DateTime('2016/01/01'));
        $user->setExperience(0);
        $manager->persist($user);

        $manager->flush();

        $this->addReference(self::ENZO, $user);

    }

}
