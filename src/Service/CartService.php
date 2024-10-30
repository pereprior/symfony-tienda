<?php

namespace App\Service;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CartService
{
    private const KEY = '_cart';
    private RequestStack $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    public function getSession(): SessionInterface
    {
        return $this->requestStack->getSession();
    }

    public function getCart(): array {
        return $this->getSession()->get(self::KEY, []);
    }

    public function add(int $id, int $quantity = 1): void
    {
        //https://symfony.com/doc/current/session.html
        $cart = $this->getCart();
        //Sólo añadimos si no lo está
        if (!array_key_exists($id, $cart))
            $cart[$id] = $quantity;
        $this->getSession()->set(self::KEY, $cart);
    }

    public function update(int $id, int $quantity): void
    {
        $cart = $this->getCart();

        $cart[$id] = $quantity;
        $this->getSession()->set(self::KEY, $cart);
    }

    public function remove(int $id): void
    {
        $cart = $this->getCart();
        unset($cart[$id]);
        $this->getSession()->set(self::KEY, $cart);
    }

    public function totalItems(): float|int
    {
        return array_sum($this->getCart());
    }
}
