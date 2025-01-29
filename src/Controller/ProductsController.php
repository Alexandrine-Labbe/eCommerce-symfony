<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ProductsController extends AbstractController
{

    public function __construct(
        private readonly ProductRepository $productRepository
    )
    {
    }

    #[Route('/{_locale<en|fr>}/')]
    #[Route('/{_locale<en|fr>}/products', name: 'products_list')]
    public function products(Request $request): Response
    {
        $products = $this->productRepository->findBy([], ['name' => 'ASC']);

        return $this->render('products/list.html.twig', [
            'products' => $products,
        ]);
    }

    #[Route('/{_locale<en|fr>}/products/{product}', name: 'products_show')]
    public function show(Product $product): Response
    {

        return $this->render('products/product.html.twig', [
            'product' => $product,
        ]);
    }
}
