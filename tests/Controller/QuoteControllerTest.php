<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class QuoteControllerTest extends WebTestCase
{
    public function test_index(): void
    {
        $client = static::createClient();

        $client->request('GET', '/quotes');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('body', 'Aucun résultat trouvé');
    }

    public function test_new(): void
    {
        $client = $this->createUserClient();
        $client->request('GET', '/quotes/new');

        $client->submitForm('Ajouter', [
            'quote[content]' => 'This is an example of quote',
            'quote[meta]' => 'Someone',
        ]);
        $client->followRedirect();

        $this->assertResponseIsSuccessful();
        $this->assertRouteSame('quote_index');
        $this->assertSelectorTextNotContains('body', 'Aucun résultat trouvé');
        $this->assertSelectorTextContains('body', 'This is an example of quote');
    }

    public function test_edit(): void
    {
        $client = $this->createUserClient();
        $client->request('GET', '/quotes/new');

        $client->submitForm('Ajouter', [
            'quote[content]' => 'This is an example of quote',
            'quote[meta]' => 'Someone',
        ]);

        $client->followRedirect();

        $client->clickLink('Modifier');

        $client->submitForm('Modifier', [
            'quote[content]' => 'This is an example of quote edited',
            'quote[meta]' => 'Someone',
        ]);

        $client->followRedirect();

        $this->assertRouteSame('quote_index');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('body', 'This is an example of quote edited');
    }

    public function test_delete(): void
    {
        $client = $this->createUserClient();
        $client->request('GET', '/quotes/new');

        $client->submitForm('Ajouter', [
            'quote[content]' => 'This is an example of quote',
            'quote[meta]' => 'Someone',
        ]);

        $client->followRedirect();

        $client->clickLink('Supprimer');
        $client->followRedirect();

        $this->assertResponseIsSuccessful();
        $this->assertRouteSame('quote_index');
        $this->assertSelectorTextContains('body', 'Aucun résultat trouvé');
    }

    private function createUserClient(): KernelBrowser
    {
        return static::createClient([], [
            'PHP_AUTH_USER' => 'john@doe',
            'PHP_AUTH_PW' => 'changeme',
        ]);
    }
}
