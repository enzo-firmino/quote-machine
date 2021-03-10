<?php

namespace App\Tests\Command;

// ...

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

class RandomQuoteCommandTest extends KernelTestCase
{
    public function test_no_category()
    {
        $kernel = static::createKernel();
        $application = new Application($kernel);

        $command = $application->find('app:random-quote');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'command' => $command->getName(),

            '--some-option' => 'option_value',
        ]);

        // la sortie de la commande dans la console
        $output = $commandTester->getDisplay();
        $this->assertContains('Username: Wouter', $output);

        // ...
    }
 
    public function test_with_category()
    {
        $kernel = static::createKernel();
        $application = new Application($kernel);

        $command = $application->find('app:random-quote');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'command' => $command->getName(),

            '--some-option' => 'option_value',
        ]);

        // la sortie de la commande dans la console
        $output = $commandTester->getDisplay();
        $this->assertContains('Username: Wouter', $output);

        // ...
    }

    public function test_no_category()
    {
        $kernel = static::createKernel();
        $application = new Application($kernel);

        $command = $application->find('app:random-quote');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'command' => $command->getName(),

            '--some-option' => 'option_value',
        ]);

        // la sortie de la commande dans la console
        $output = $commandTester->getDisplay();
        $this->assertContains('Username: Wouter', $output);

        // ...
    }

    public function test_no_category()
    {
        $kernel = static::createKernel();
        $application = new Application($kernel);

        $command = $application->find('app:random-quote');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'command' => $command->getName(),

            '--some-option' => 'option_value',
        ]);

        // la sortie de la commande dans la console
        $output = $commandTester->getDisplay();
        $this->assertContains('Username: Wouter', $output);

        // ...
    }
}
