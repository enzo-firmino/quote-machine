<?php

namespace App\Tests\Api;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class UsersTest extends ApiTestCase
{
    /**
     * @test
     */
    public function i_can_list_users(): void
    {
        $client = static::createClient();

        $response = $client->request('GET', '/api/users');

        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        $this->assertCount(3, $response->toArray()['hydra:member']);
        $this->assertMatchesResourceCollectionJsonSchema(User::class);
    }

    /**
     * @test
     */
    public function i_can_get_a_user(): void
    {
        $client = static::createClient();
        $user = $this->getEntityManager()->getRepository(User::class)->findOneByEmail('john@doe');

        $client->request('GET', sprintf('/api/users/%d', $user->getId()));

        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        $this->assertJsonContains([
            '@context' => '/api/contexts/User',
            '@type' => 'User',
            'name' => 'John Doe',
        ]);

        $this->assertMatchesResourceItemJsonSchema(User::class);
    }

    private function getEntityManager(): EntityManagerInterface
    {
        return self::$container->get('doctrine')->getManagerForClass(User::class);
    }
}
