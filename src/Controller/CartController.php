<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\CartService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CartController extends AbstractController
{
    public function __construct(
        private readonly CartService $cartService,
    )
    {
    }

    #[Route('/cart', name: 'cart')]
    public function index(): Response
    {
        $cartDetails = $this->cartService->getCartDetails();

        return $this->render('cart/cart.html.twig', [
            'cartDetails' => $cartDetails,
        ]);
    }

    #[Route('/cart/add', name: 'add_to_cart')]
    public function addToCart(Request $request): Response
    {
        $productId = (int)$request->request->get('productId');
        $quantity = (int)$request->request->get('quantity');
        $this->cartService->addProduct($productId, $quantity);

        return $this->redirectToRoute('products_show', ['product' => $productId]);
    }
}
