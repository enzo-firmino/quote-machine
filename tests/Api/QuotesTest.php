<?php

namespace App\Tests\Api;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\Quote;
use Doctrine\ORM\EntityManagerInterface;

class QuotesTest extends ApiTestCase
{
    /**
     * @test
     */
    public function i_can_list_quotes(): void
    {
        $client = static::createClient();

        $response = $client->request('GET', '/api/quotes');

        $this->assertCount(0, $response->toArray()['hydra:member']);
        $this->assertResponseIsSuccessful();
        $this->assertMatchesResourceCollectionJsonSchema(Quote::class);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');

        $this->createQuote();
        $response = $client->request('GET', '/api/quotes');

        $this->assertCount(1, $response->toArray()['hydra:member']);
    }

    /**
     * @test
     */
    public function i_can_get_quote(): void
    {
        $client = static::createClient();
        $quote = $this->createQuote();

        $client->request('GET', sprintf('/api/quotes/%d', $quote->getId()));

        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        $this->assertJsonContains([
            '@context' => '/api/contexts/Quote',
            '@type' => 'Quote',
            'content' => 'Une pluie de pierres en intérieur donc ! Je vous prenais pour un pied de chaise mais vous êtes un précurseur en fait !',
            'meta' => 'Élias de Kelliwic\'h, Livre IV, Le Privilégié',
        ]);

        $this->assertMatchesResourceItemJsonSchema(Quote::class);
    }

    /**
     * @test
     */
    public function as_user_i_can_create_quote(): void
    {
        $client = static::createClient([], [
            'auth_basic' => ['contact@nclshart.net', 'changeme'],
        ]);

        $client->request('POST', '/api/quotes', ['json' => [
            'content' => 'Sire, Sire ! On en a gros !',
            'meta' => 'Perceval, Livre II, Les Exploités',
        ]]);

        $this->assertResponseStatusCodeSame(201);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        $this->assertJsonContains([
            '@context' => '/api/contexts/Quote',
            '@type' => 'Quote',
            'content' => 'Sire, Sire ! On en a gros !',
            'meta' => 'Perceval, Livre II, Les Exploités',
        ]);
        $this->assertMatchesResourceItemJsonSchema(Quote::class);
    }

    /**
     * @test
     */
    public function as_user_i_can_edit_quote(): void
    {
        $client = static::createClient([], [
            'auth_basic' => ['john@doe', 'changeme'],
        ]);
        $quote = $this->createQuote();

        $client->request('PUT', sprintf('/api/quotes/%d', $quote->getId()), ['json' => [
            'content' => 'Nouveau contenu',
            'meta' => 'Nouvelle meta',
        ]]);

        $this->assertResponseStatusCodeSame(200);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        $this->assertJsonContains([
            '@context' => '/api/contexts/Quote',
            '@type' => 'Quote',
            'content' => 'Nouveau contenu',
            'meta' => 'Nouvelle meta',
        ]);
        $this->assertMatchesResourceItemJsonSchema(Quote::class);
    }

    /**
     * @test
     */
    public function as_user_i_can_delete_quote(): void
    {
        $client = static::createClient([], [
            'auth_basic' => ['john@doe', 'changeme'],
        ]);
        $quote = $this->createQuote();

        $client->request('DELETE', sprintf('/api/quotes/%d', $quote->getId()));

        $this->assertResponseStatusCodeSame(204);
    }

    private function createQuote(): Quote
    {
        $em = $this->getEntityManager();

        $quote = new Quote();
        $quote->setContent('Une pluie de pierres en intérieur donc ! Je vous prenais pour un pied de chaise mais vous êtes un précurseur en fait !');
        $quote->setMeta('Élias de Kelliwic\'h, Livre IV, Le Privilégié');
        $em->persist($quote);

        $em->flush();

        return $quote;
    }

    private function getEntityManager(): EntityManagerInterface
    {
        return self::$container->get('doctrine')->getManagerForClass(Quote::class);
    }
}
