<?php

declare(strict_types=1);

namespace App\Tests\Controller;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ProductsControllerTest extends WebTestCase
{
    public function testHomePage(): void
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Products');

        $this->assertEquals(12, $crawler->filter('.product')->count());
    }

    public function testProducts(): void
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Products');

        $this->assertEquals(12, $crawler->filter('.product')->count());
    }

    public function testProductShow(): void
    {
        $client = static::createClient();
        $entityManager = static::getContainer()->get(EntityManagerInterface::class);

        $product = $entityManager->getRepository(Product::class)->findOneBy([]);

        $client->request('GET', '/products/' . $product->getId());

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', $product->getName());
    }

    public function testNoProductShow(): void
    {
        $client = static::createClient();

        $client->request('GET', '/products/NOPE');
        $this->assertResponseStatusCodeSame(404);
    }
}
