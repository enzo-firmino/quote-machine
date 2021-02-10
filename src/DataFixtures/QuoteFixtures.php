<?php

namespace App\DataFixtures;

use App\Entity\Quote;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class QuoteFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $quote = new Quote();
        $quote->setContent('Qu\'est-ce que vous voulez-vous insinuyer Sire ?');
        $quote->setMeta('Roparzh, Kaamelott, Livre III, 74 : Saponides et detergents');
        $quote->setCategory($this->getReference(CategoryFixtures::KAAMELOTT));
        $quote->setAuteur($this->getReference(UserFixtures::ENZO));
        $quote->setDateCreation(new \DateTime('2016/01/01'));
        $manager->persist($quote);

        $quote = new Quote();
        $quote->setContent('Sire, Sire ! On en a gros !');
        $quote->setMeta('Perceval, Kaamelott, Livre II, Les Exploités');
        $quote->setCategory($this->getReference(CategoryFixtures::KAAMELOTT));
        $quote->setAuteur($this->getReference(UserFixtures::ENZO));
        $quote->setDateCreation(new \DateTime('2016/01/01'));
        $manager->persist($quote);

        $quote = new Quote();
        $quote->setContent('Mais évidemment c\'est sans alcool !');
        $quote->setMeta('Merlin, Kaamelott, Livre II, 4 : Le rassemblement du corbeau');
        $quote->setCategory($this->getReference(CategoryFixtures::KAAMELOTT));
        $quote->setAuteur($this->getReference(UserFixtures::ENZO));
        $quote->setDateCreation(new \DateTime('2016/01/01'));
        $manager->persist($quote);

        $quote = new Quote();
        $quote->setContent('Quand on veut être sûr de son coup, Seigneur Dagonet… on plante des navets. On ne pratique pas le putsch.');
        $quote->setMeta('Loth, Kaamelott, Livre V, Les Repentants');
        $quote->setCategory($this->getReference(CategoryFixtures::KAAMELOTT));
        $quote->setAuteur($this->getReference(UserFixtures::ENZO));
        $quote->setDateCreation(new \DateTime('2016/01/01'));
        $manager->persist($quote);

        $quote = new Quote();
        $quote->setContent('Vous savez c\'que c\'est, mon problème ? Trop gentil.');
        $quote->setMeta('Léodagan, Kaamelott, Livre II, Le complot');
        $quote->setCategory($this->getReference(CategoryFixtures::KAAMELOTT));
        $quote->setAuteur($this->getReference(UserFixtures::ENZO));
        $quote->setDateCreation(new \DateTime('2016/01/01'));
        $manager->persist($quote);

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            CategoryFixtures::class,
            UserFixtures::class,
        ];
    }
}
