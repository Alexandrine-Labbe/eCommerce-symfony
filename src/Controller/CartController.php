<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Product;
use App\Service\CartService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class CartController extends AbstractController
{
    public function __construct(
        private readonly CartService $cartService,
        private readonly TranslatorInterface $translator,
    )
    {
    }

    #[Route('/{_locale<en|fr>}/cart', name: 'cart')]
    public function cart(): Response
    {
        $cartDetails = $this->cartService->getCartDetails();

        return $this->render('cart/cart.html.twig', [
            'cartDetails' => $cartDetails,
        ]);
    }

    #[Route('/{_locale<en|fr>}/cart/add/{product}', name: 'add_to_cart')]
    public function addToCart(Product $product, Request $request): Response
    {
        $quantity = (int)$request->request->get('quantity', 1);
        $this->cartService->addToCart($product->getId(), $quantity);
        $this->addFlash('success', $this->translator->trans('CART.ADD_SUCCESS'));

        $referer = $request->headers->get('referer');

        return $this->redirect($referer);
    }

    #[Route('/{_locale<en|fr>}/cart/decrease/{product}', name: 'decrease_to_cart')]
    public function decreaseToCart(Product $product, Request $request): Response
    {
        $quantity = (int)$request->request->get('quantity', 1);
        $this->cartService->decreaseFromCart($product->getId(), $quantity);
        $this->addFlash('success', $this->translator->trans('CART.DECREASE_SUCCESS'));

        $referer = $request->headers->get('referer');

        return $this->redirect($referer);
    }

    #[Route('/{_locale<en|fr>}/cart/remove/{product}', name: 'remove_from_cart')]
    public function removeFromCart(Product $product, Request $request): Response
    {
        $this->cartService->deleteFromCart($product->getId());
        $this->addFlash('success', $this->translator->trans('CART.REMOVE_SUCCESS'));

        $referer = $request->headers->get('referer');

        return $this->redirect($referer);
    }
}
