<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture implements FixtureGroupInterface
{
    public const NAME = 'enzo';

    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setName('Enzo');
        $user->setEmail('firmino.enzo@hotmail.fr');
        $user->setPassword('symfony');
        $manager->persist($user);

        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['tests'];
    }
}
