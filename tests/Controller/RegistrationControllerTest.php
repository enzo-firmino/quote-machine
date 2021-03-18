<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RegistrationControllerTest extends WebTestCase
{
    public function test_register(): void
    {
        $client = static::createClient();

        $client->request('GET', '/register');

        $client->submitForm('Créer un compte', [
            'registration_form[email]' => 'test@example.org',
            'registration_form[name]' => 'My name',
            'registration_form[plainPassword]' => 'changeme',
        ]);

        $this->assertEmailCount(1);
        $this->assertEmailAddressContains($this->getMailerMessage(), 'To', 'test@example.org');

        $client->followRedirect();
        $client->followRedirect();

        $this->assertResponseIsSuccessful();
        $this->assertRouteSame('quote_index');
        $this->assertSelectorTextContains('body', 'Se déconnecter');
    }

}
