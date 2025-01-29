<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

class ApiController extends AbstractController
{

    public function __construct(
        private readonly SerializerInterface $serializer,
        private readonly ProductRepository $productRepository
    )
    {
    }

    #[Route('/api/products.json', name: 'api_products_json')]
    public function getProducts(): JsonResponse
    {
        $products = $this->productRepository->findBy([], ['name' => 'ASC']);
        $jsonContent = $this->serializer->serialize($products, 'json');

        return JsonResponse::fromJsonString($jsonContent);
    }
}