<?php

declare(strict_types=1);

namespace App\Tests\Controller;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\JsonResponse;

class ApiControllerTest extends WebTestCase
{
    public function testApiProducts(): void
    {
        $client = static::createClient();

        $client->request('GET', '/api/products.json', ['headers' => ['Accept' => 'application/json']]);

        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('content-type', 'application/json');
        $rep = $client->getResponse();
        $json = $rep->getContent();
        $this->assertJson($json);

        $entityManager = static::getContainer()->get(EntityManagerInterface::class);

        $products = $entityManager->getRepository(Product::class)->findBy([], ['name' => 'ASC']);
        $firstProductArray = [
            'id' => $products[0]->getId(),
            'name' => $products[0]->getName(),
            'description' => $products[0]->getDescription(),
            'price' => $products[0]->getPrice(),
            'priceCents' => $products[0]->getPriceCents(),
            'slug' => $products[0]->getSlug(),
            'image' => $products[0]->getImage(),
        ];
        $this->assertEquals($firstProductArray, json_decode($json, true)[0]);
    }
}
