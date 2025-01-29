<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Product;
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

    #[Route('/cart/add/{product}', name: 'add_to_cart')]
    public function addToCart(Product $product, Request $request): Response
    {
        $quantity = (int)$request->request->get('quantity', 1);
        $this->cartService->addToCart($product->getId(), $quantity);
        $this->addFlash('success', 'Un article a bien été ajouté à votre panier');

        $referer = $request->headers->get('referer');

        return $this->redirect($referer);
    }

    #[Route('/cart/decrease/{product}', name: 'decrease_to_cart')]
    public function decreaseToCart(Product $product, Request $request): Response
    {
        $quantity = (int)$request->request->get('quantity', 1);
        $this->cartService->decreaseFromCart($product->getId(), $quantity);
        $this->addFlash('success', 'Un article a bien été déduit de votre panier');

        $referer = $request->headers->get('referer');

        return $this->redirect($referer);
    }

    #[Route('/cart/remove/{product}', name: 'remove_from_cart')]
    public function removeFromCart(Product $product, Request $request): Response
    {
        $this->cartService->deleteFromCart($product->getId());
        $this->addFlash('success', 'Un article a bien été supprimé de votre panier');

        $referer = $request->headers->get('referer');

        return $this->redirect($referer);
    }
}
