<?php

declare(strict_types=1);

namespace App\Tests\Controller;

use App\Entity\Product;
use App\Service\CartService;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CartControllerTest extends WebTestCase
{
    public function testEmptyCart(): void
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/fr/cart');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Mon panier');

        $this->assertEquals(0, $crawler->filter('tbody tr')->count());
    }

    public function testCart(): void
    {
        $client = static::createClient();

       $this->setFakeCart();

        $crawler = $client->request('GET', '/fr/cart');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Mon panier');

        $this->assertEquals(2, $crawler->filter('.cart-item')->count());
    }

    private function setFakeCart()
    {
        $product1 = new Product();
        $product1
            ->setId(1)
            ->setName('Product 1')
            ->setPriceCents(1000)
            ->setSlug('product-1');

        $product2 = new Product();
        $product2
            ->setId(2)
            ->setName('Product 2')
            ->setPriceCents(1550)
            ->setSlug('product-2');

        $mockCartService = $this->createMock(CartService::class);
        $mockCartService->method('getCartDetails')->willReturn([
            [
                'product' => $product1,
                'quantity' => 2,
                'total_cents' => 2000,
                'total' => 20,
            ],
            [
                'product' => $product2,
                'quantity' => 1,
                'total_cents' => 1550,
                'total' => 15.50,
            ],
        ]);

        self::getContainer()->set(CartService::class, $mockCartService);
    }
}
