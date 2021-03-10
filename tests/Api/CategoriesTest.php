<?php

namespace App\Tests\Api;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\Category;
use Doctrine\ORM\EntityManagerInterface;

class CategoriesTest extends ApiTestCase
{
    /**
     * @test
     */
    public function i_can_list_categories(): void
    {
        $client = static::createClient();

        $client->request('GET', '/api/categories');

        $this->assertResponseIsSuccessful();
        $this->assertMatchesResourceCollectionJsonSchema(Category::class);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
    }

    /**
     * @test
     */
    public function i_can_get_category(): void
    {
        $client = static::createClient();
        $category = $this->createCategory();

        $client->request('GET', sprintf('/api/categories/%d', $category->getId()));

        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        $this->assertJsonContains([
            '@context' => '/api/contexts/Category',
            '@type' => 'Category',
            'name' => 'API Platform',
        ]);

        $this->assertMatchesResourceItemJsonSchema(Category::class);
    }

    /**
     * @test
     */
    public function as_admin_i_can_create_category(): void
    {
        $client = static::createClient([], [
            'auth_basic' => ['contact@floran.fr', 'changeme'],
        ]);

        $client->request('POST', '/api/categories', ['json' => [
            'name' => 'Kaamelott',
        ]]);

        $this->assertResponseStatusCodeSame(201);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        $this->assertJsonContains([
            '@context' => '/api/contexts/Category',
            '@type' => 'Category',
            'name' => 'Kaamelott',
        ]);
        $this->assertMatchesResourceItemJsonSchema(Category::class);
    }

    /**
     * @test
     */
    public function as_user_i_cannot_create_category(): void
    {
        $client = static::createClient([], [
            'auth_basic' => ['john@doe', 'changeme'],
        ]);

        $client->request('POST', '/api/categories', ['json' => [
            'name' => 'Kaamelott',
        ]]);
        $this->assertResponseStatusCodeSame(403);
    }

    /**
     * @test
     */
    public function as_admin_i_can_edit_category(): void
    {
        $client = static::createClient([], [
            'auth_basic' => ['contact@floran.fr', 'changeme'],
        ]);
        $category = $this->createCategory();

        $client->request('PUT', sprintf('/api/categories/%d', $category->getId()), ['json' => [
            'name' => 'Nouveau nom',
        ]]);

        $this->assertResponseStatusCodeSame(200);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        $this->assertJsonContains([
            '@context' => '/api/contexts/Category',
            '@type' => 'Category',
            'name' => 'Nouveau nom',
        ]);
        $this->assertMatchesResourceItemJsonSchema(Category::class);
    }

    /**
     * @test
     */
    public function as_user_i_cannot_edit_category(): void
    {
        $client = static::createClient([], [
            'auth_basic' => ['john@doe', 'changeme'],
        ]);
        $category = $this->createCategory();

        $client->request('PUT', sprintf('/api/categories/%d', $category->getId()), ['json' => [
            'name' => 'Nouveau nom',
        ]]);

        $this->assertResponseStatusCodeSame(403);
    }

    /**
     * @test
     */
    public function as_admin_i_can_delete_category(): void
    {
        $client = static::createClient([], [
            'auth_basic' => ['contact@floran.fr', 'changeme'],
        ]);
        $category = $this->createCategory();

        $client->request('DELETE', sprintf('/api/categories/%d', $category->getId()));

        $this->assertResponseStatusCodeSame(204);
    }

    /**
     * @test
     */
    public function as_user_i_cannot_delete_category(): void
    {
        $client = static::createClient([], [
            'auth_basic' => ['john@doe', 'changeme'],
        ]);
        $category = $this->createCategory();

        $client->request('DELETE', sprintf('/api/categories/%d', $category->getId()));

        $this->assertResponseStatusCodeSame(403);
    }

    private function createCategory(): Category
    {
        $em = $this->getEntityManager();

        $category = new Category();
        $category->setName('API Platform');
        $em->persist($category);

        $em->flush();

        return $category;
    }

    private function getEntityManager(): EntityManagerInterface
    {
        return self::$container->get('doctrine')->getManagerForClass(Category::class);
    }
}
