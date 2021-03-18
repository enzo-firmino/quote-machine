<?php

namespace App\Tests\Command;

// ...

use App\Entity\Category;
use App\Entity\Quote;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

class RandomQuoteCommandTest extends KernelTestCase
{
    /**
     * @var Application
     */
    private $application;

    public function setUp(): void
    {
        $kernel = static::bootKernel();
        $this->application = new Application($kernel);
    }

    public function test_no_category()
    {
        $quote = (new Quote())->setContent('Vous savez c\'que c\'est, mon problème ? Trop gentil.')
            ->setMeta('Léodagan, Kaamelott, Livre II, Le complot')
            ->setAuteur($this->createUser());
        $this->persistAndFlush($quote);

        $command = $this->application->find('app:random-quote');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'command' => $command->getName(),
        ]);

        $output = $commandTester->getDisplay();
        $this->assertStringContainsString('Vous savez c\'que c\'est, mon problème ? Trop gentil.', $output);
    }

    public function test_with_category()
    {
        $quote = (new Quote())->setContent('Test quote quote')
            ->setMeta('Oui oui,')
            ->setAuteur($this->createUser());
        $this->persistAndFlush($quote);

        $quote = (new Quote())->setContent('Vous savez c\'que c\'est, mon problème ? Trop gentil.')
            ->setMeta('Léodagan, Kaamelott, Livre II, Le complot')
            ->setCategory($this->createCategory())
            ->setAuteur($this->createUser());
        $this->persistAndFlush($quote);

        $command = $this->application->find('app:random-quote');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'command' => $command->getName(),
            '--category' => 'Kaamelott',
        ]);

        $output = $commandTester->getDisplay();
        $this->assertStringContainsString('Vous savez c\'que c\'est, mon problème ? Trop gentil.', $output);
    }

    public function test_with_false_category()
    {
        $quote = (new Quote())->setContent('Test quote quote')
            ->setMeta('Oui oui,')
            ->setAuteur($this->createUser());
        $this->persistAndFlush($quote);

        $command = $this->application->find('app:random-quote');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'command' => $command->getName(),
            '--category' => 'Kamelote',
        ]);

        // la sortie de la commande dans la console
        $output = $commandTester->getDisplay();
        $this->assertStringContainsString('Unknown Kamelote category', $output);

    }

    public function test_no_quote()
    {
        $command = $this->application->find('app:random-quote');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'command' => $command->getName(),
        ]);

        $output = $commandTester->getDisplay();
        $this->assertStringContainsString('Aucune citation trouvée', $output);
    }

    private function createUser(): User
    {
        $user = (new User())
            ->setName('Robert')
            ->setPassword('azerty')
            ->setEmail('robert@robert');

        $this->persistAndFlush($user);

        return $user;
    }

    private function createCategory(): Category
    {
        $category = (new Category())
            ->setName('Kaamelott');

        $this->persistAndFlush($category);

        return $category;
    }

    private function persistAndFlush($entity): void
    {
        $em = $this->application->getKernel()
            ->getContainer()
            ->get('doctrine')
            ->getManager();

        $em->persist($entity);
        $em->flush();
    }
}
