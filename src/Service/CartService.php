<?php

declare(strict_types=1);

namespace App\Service;

use App\Repository\ProductRepository;
use InvalidArgumentException;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CartService
{
    public const CART_KEY = 'cart';

    public function __construct(
        private readonly RequestStack $requestStack,
        private readonly ProductRepository $productRepository,
    )
    { }

    public function addToCart(int $productId, int $quantity = 1): void
    {
        $session = $this->requestStack->getSession();
        $cart = $session->get(self::CART_KEY, []);
        if ($quantity < 0) {
            throw new InvalidArgumentException('La valeur doit être un entier positif.');
        }
        if (isset($cart[$productId])) {
            $cart[$productId] += $quantity;
        } else {
            $cart[$productId] = $quantity;
        }

        $session->set(self::CART_KEY, $cart);
    }

    public function decreaseFromCart(int $productId, int $quantity = 1): void
    {
        $session = $this->requestStack->getSession();
        $cart = $session->get(self::CART_KEY, []);
        if ($quantity < 0) {
            throw new InvalidArgumentException('La valeur doit être un entier positif.');
        }
        if (isset($cart[$productId])) {
            $cart[$productId] -= $quantity;
            if ($cart[$productId] <= 0) {
                unset($cart[$productId]);
            }
        }

        $session->set(self::CART_KEY, $cart);
    }

    public function deleteFromCart(int $productId): void
    {
        $session = $this->requestStack->getSession();
        $cart = $session->get(self::CART_KEY, []);
        unset($cart[$productId]);
        $session->set(self::CART_KEY, $cart);
    }

    public function clearCart(): void
    {
        $session = $this->requestStack->getSession();
        $session->remove(self::CART_KEY);
    }

    public function getCart(): array
    {
        $session = $this->requestStack->getSession();
        return $session->get(self::CART_KEY, []);
    }

    public function getCartDetails(): array
    {
        $cart = $this->getCart();
        $cartDetails = [];

        foreach ($cart as $productId => $quantity) {
            $product = $this->productRepository->find($productId);
            if ($product) {
                $cartDetails[] = [
                    'product' => $product,
                    'quantity' => $quantity,
                    'total_cents' => $product->getPriceCents() * $quantity,
                    'total' => ($product->getPriceCents() * $quantity) / 100,
                ];
            }
        }

        return $cartDetails;
    }

    public function getQuantity(): int
    {
        $cart = $this->getCart();
        $quantity = 0;
        foreach ($cart as $productId => $_productQuantity) {
            $quantity += $_productQuantity;
        }

        return $quantity;
    }

    public function getTotal(): float
    {
        $cart = $this->getCart();
        $total = 0;
        foreach ($cart as $productId => $_productQuantity) {
            $product = $this->productRepository->find($productId);
            if ($product) {
                $total += $_productQuantity * $product->getPriceCents();
            }
        }

        return $total / 100;
    }
}