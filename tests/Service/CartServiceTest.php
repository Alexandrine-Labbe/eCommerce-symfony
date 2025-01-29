<?php

namespace App\Tests\Service;

use App\Entity\Product;
use App\Repository\ProductRepository;
use App\Service\CartService;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;

class CartServiceTest extends TestCase
{
    private $session;
    private $requestStack;
    private $productRepository;
    private $cartService;

    protected function setUp(): void
    {
        $this->session = new Session(new MockArraySessionStorage());
        $this->requestStack = $this->createMock(RequestStack::class);
        $this->productRepository = $this->createMock(ProductRepository::class);
        $this->cartService = new CartService($this->requestStack, $this->productRepository);

        $this->requestStack->method('getSession')->willReturn($this->session);
    }

    // addToCart
    public function testAddToCart(): void
    {
        $this->cartService->addToCart(1, 2);

        $cart = $this->cartService->getCart();
        $this->assertEquals([1 => 2], $cart);
    }

    public function testAddMultipleToCart(): void
    {
        $this->cartService->addToCart(1, 2);
        $this->cartService->addToCart(1, 1);

        $cart = $this->cartService->getCart();
        $this->assertEquals([1 => 3], $cart);
    }

    public function testAddDifferentToCart(): void
    {
        $this->cartService->addToCart(1, 2);
        $this->cartService->addToCart(2, 1);

        $cart = $this->cartService->getCart();
        $this->assertEquals([1 => 2, 2 => 1], $cart);
    }

    public function testAddNegativeToCart(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->cartService->addToCart(1, -2);

        $cart = $this->cartService->getCart();
        $this->assertEquals([], $cart);
    }

    // decreaseFromCart
    public function testDecreaseOneFromCart(): void
    {
        $this->cartService->addToCart(1, 2);
        $this->cartService->decreaseFromCart(1, 1);

        $cart = $this->cartService->getCart();
        $this->assertEquals([1 => 1], $cart);
    }

    public function testDecreaseNoneFromCart(): void
    {
        $this->cartService->addToCart(1, 2);
        $this->cartService->decreaseFromCart(1, 0);

        $cart = $this->cartService->getCart();
        $this->assertEquals([1 => 2], $cart);
    }

    public function testDecreaseAllFromCart(): void
    {
        $this->cartService->addToCart(1, 2);
        $this->cartService->decreaseFromCart(1, 2);

        $cart = $this->cartService->getCart();
        $this->assertEquals([], $cart);
    }

    public function testDecreaseTooMuchFromCart(): void
    {
        $this->cartService->addToCart(1, 2);
        $this->cartService->decreaseFromCart(1, 3);

        $cart = $this->cartService->getCart();
        $this->assertEquals([], $cart);
    }

    public function testDecreaseFromCart(): void
    {
        $this->cartService->addToCart(1, 2);
        $this->cartService->decreaseFromCart(1);

        $cart = $this->cartService->getCart();
        $this->assertEquals([1 => 1], $cart);
    }

    public function testDecreaseNonExistingFromCart(): void
    {
        $this->cartService->addToCart(1, 2);
        $this->cartService->decreaseFromCart(2, 5);

        $cart = $this->cartService->getCart();
        $this->assertEquals([1 => 2], $cart);
    }

    public function testDecreaseNegativeFromCart(): void
    {
        $this->cartService->addToCart(1, 2);
        $this->expectException(InvalidArgumentException::class);
        $this->cartService->decreaseFromCart(1, -2);

        $cart = $this->cartService->getCart();
        $this->assertEquals([], $cart);
    }

    // deleteFromCart
    public function testDeleteFromCart(): void
    {
        $this->cartService->addToCart(1, 2);
        $this->cartService->deleteFromCart(1);

        $cart = $this->cartService->getCart();
        $this->assertEquals([], $cart);
    }

    public function testDeleteNonExistingFromCart(): void
    {
        $this->cartService->addToCart(1, 2);
        $this->cartService->deleteFromCart(2);

        $cart = $this->cartService->getCart();
        $this->assertEquals([1 => 2], $cart);
    }

    // clearCart
    public function testClearCart(): void
    {
        $this->cartService->addToCart(1, 2);
        $this->cartService->clearCart();

        $cart = $this->cartService->getCart();
        $this->assertEquals([], $cart);
    }

    public function testClearEmptyCart(): void
    {
        $this->cartService->clearCart();

        $cart = $this->cartService->getCart();
        $this->assertEquals([], $cart);
    }

    public function testClearBigCart()
    {
        $this->cartService->addToCart(1, 2);
        $this->cartService->addToCart(2, 1);
        $this->cartService->addToCart(3, 4);
        $this->cartService->addToCart(4, 3);
        $this->cartService->addToCart(5, 2);
        $this->cartService->addToCart(6, 1);

        $this->cartService->clearCart();

        $cart = $this->cartService->getCart();
        $this->assertEquals([], $cart);
    }

    // getCart
    public function testGetCart(): void
    {
        $this->cartService->addToCart(1, 2);
        $this->cartService->addToCart(2, 1);

        $cart = $this->cartService->getCart();
        $this->assertEquals([1 => 2, 2 => 1], $cart);
    }

    public function testGetEmptyCart(): void
    {
        $cart = $this->cartService->getCart();
        $this->assertEquals([], $cart);
    }

    public function testGetupdatedCart(): void
    {
        $this->cartService->addToCart(1, 2);
        $this->cartService->addToCart(2, 3);
        $this->cartService->addToCart(3, 4);
        $this->cartService->decreaseFromCart(2, 1);
        $this->cartService->deleteFromCart(3);


        $cart = $this->cartService->getCart();
        $this->assertEquals([1 => 2, 2 => 2], $cart);
    }

    // getCartDetails
    public function testGetCartDetails(): void
    {
        $product1 = new Product();
        $product1
            ->setId(1)
            ->setName('Product 1')
            ->setPriceCents(1000);

        $product2 = new Product();
        $product2
            ->setId(2)
            ->setName('Product 2')
            ->setPriceCents(1550);

        $this->cartService->addToCart($product1->getId(), 2);
        $this->cartService->addToCart($product2->getId(), 1);

        $this->productRepository->method('find')
            ->willReturnCallback(function ($id) use ($product1, $product2) {
                return match ($id) {
                    1 => $product1,
                    2 => $product2,
                    default => null,
                };
            });

        $cartDetails = $this->cartService->getCartDetails();
        $this->assertEquals([
            [
                'product' => $product1,
                'quantity' => 2,
                'total' => 2000,
            ],
            [
                'product' => $product2,
                'quantity' => 1,
                'total' => 1550,
            ],
        ], $cartDetails);
    }

    public function testGetEmptyCartDetails(): void
    {
        $cartDetails = $this->cartService->getCartDetails();
        $this->assertEquals([], $cartDetails);
    }

    public function testGetCartDetailsWithNonExistingProduct(): void
    {
        $this->cartService->addToCart(999, 2);

        $this->productRepository->method('find')
            ->willReturn(null);

        $cartDetails = $this->cartService->getCartDetails();
        $this->assertEquals([], $cartDetails);
    }

    // getQuantity
    public function testGetQuantity(): void
    {
        $this->cartService->addToCart(1, 2);
        $this->cartService->addToCart(2, 1);

        $quantity = $this->cartService->getQuantity();
        $this->assertEquals(3, $quantity);
    }

    public function testGetEmptyQuantity(): void
    {
        $quantity = $this->cartService->getQuantity();
        $this->assertEquals(0, $quantity);
    }

    // getTotal
    public function testGetTotal(): void
    {
        $product1 = new Product();
        $product1
            ->setId(1)
            ->setName('Product 1')
            ->setPriceCents(1000);

        $product2 = new Product();
        $product2
            ->setId(2)
            ->setName('Product 2')
            ->setPriceCents(1550);

        $this->cartService->addToCart($product1->getId(), 2);
        $this->cartService->addToCart($product2->getId(), 1);

        $this->productRepository->method('find')
            ->willReturnCallback(function ($id) use ($product1, $product2) {
                return match ($id) {
                    1 => $product1,
                    2 => $product2,
                    default => null,
                };
            });

        $total = $this->cartService->getTotal();
        $this->assertEquals(35.50, $total);
    }

    public function testGetEmptyTotal(): void
    {
        $total = $this->cartService->getTotal();
        $this->assertEquals(0.00, $total);
    }
}
