<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ProductsController extends AbstractController
{

    public function __construct(
        private readonly ProductRepository $productRepository
    )
    {
    }

    #[Route('/products', name: 'product_list')]
    public function products(): Response
    {
        $products = $this->productRepository->findBy([], ['name' => 'ASC']);

        return $this->render('products/list.html.twig', [
            'products' => $products,
        ]);
    }
}
