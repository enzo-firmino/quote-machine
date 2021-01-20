<?php

namespace App\Tests\Controller\Controller;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CategoryControllerTest extends WebTestCase
{
    public function test_new(): void
    {
        $client = $this->createAdminClient();
        $client->request('GET', '/category/new');

        $client->submitForm('Ajouter', [
            'category[name]' => 'This is an example of category',
        ]);

        $client->followRedirect();

        $this->assertResponseIsSuccessful();
        $this->assertRouteSame('quote_index');
        $this->assertSame(1, $this->getRepository()->countByName('This is an example of category'));
    }

    public function test_show(): void
    {
        $client = static::createAdminClient();

        $category = $this->createCategory('This is an example of category');

        $client->request('GET', sprintf('/category/%s', $category->getId()));

        $this->assertResponseIsSuccessful();
        $this->assertRouteSame('category_show');
        $this->assertSame(1, $this->getRepository()->countByName('This is an example of category'));
    }

    public function test_edit(): void
    {
        $client = $this->createAdminClient();

        $category = $this->createCategory('This is an example of category');

        $client->request('GET', sprintf('/category/%s/edit', $category->getId()));

        $client->submitForm('Modifier', [
            'category[name]' => 'This is an edited example of category',
        ]);

        $client->followRedirect();

        $this->assertResponseIsSuccessful();
        $this->assertRouteSame('quote_index');
        $this->assertSame(0, $this->getRepository()->countByName('This is an example of category'));
        $this->assertSame(1, $this->getRepository()->countByName('This is an edited example of category'));
    }

    public function test_delete(): void
    {
        $client = $this->createAdminClient();

        $category = $this->createCategory('This is an example of category');

        $client->request('GET', sprintf('/category/%s', $category->getId()));

        $client->submitForm('Supprimer la catégorie');

        $client->followRedirect();

        $this->assertResponseIsSuccessful();
        $this->assertRouteSame('quote_index');
        $this->assertSame(0, $this->getRepository()->countByName('This is an example of category'));
    }

    private function createAdminClient(): KernelBrowser
    {
        return static::createClient([], [
            'PHP_AUTH_USER' => 'contact@floran.fr',
            'PHP_AUTH_PW' => 'changeme',
        ]);
    }

    private function createCategory(string $name): Category
    {
        $category = new Category();
        $category->setName($name);

        $entityManager = $this->getEntityManager();
        $entityManager->persist($category);
        $entityManager->flush();

        return $category;
    }

    private function getEntityManager(): EntityManagerInterface
    {
        return self::$container->get('doctrine')->getManagerForClass(Category::class);
    }

    private function getRepository(): CategoryRepository
    {
        return self::$container->get('doctrine')->getRepository(Category::class);
    }
}
