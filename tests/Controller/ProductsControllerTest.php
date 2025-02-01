<?php

declare(strict_types=1);

namespace App\Tests\Controller;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ProductsControllerTest extends WebTestCase
{
    public function testHomePage(): void
    {
        $client = static::createClient();

        $client->request('GET', '/');

        $this->assertResponseRedirects('/fr/products');
    }

    public function testProducts(): void
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/fr/products');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Produits');

        $this->assertEquals(12, $crawler->filter('.product-card')->count());
    }

    public function testProductShow(): void
    {
        $client = static::createClient();
        $entityManager = static::getContainer()->get(EntityManagerInterface::class);

        $product = $entityManager->getRepository(Product::class)->findOneBy([]);

        $client->request('GET', '/fr/products/' . $product->getSlug());

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', $product->getName());
    }

    public function testNoProductShow(): void
    {
        $client = static::createClient();

        $client->request('GET', '/fr/products/NOPE');
        $this->assertResponseStatusCodeSame(404);
    }
}
