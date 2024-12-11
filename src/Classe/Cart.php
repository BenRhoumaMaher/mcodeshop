<?php

namespace App\Classe;

use Symfony\Component\HttpFoundation\RequestStack;

class Cart
{
    public function __construct(private RequestStack $requestStack)
    {
    }

    public function add($product)
    {
        // Retrieve the cart from the session
        $cart = $this->requestStack->getSession()->get('cart');

        // Check if the product is already in the cart
        if (isset($cart[$product->getId()])) {
            $cart[$product->getId()] = [
                'object' => $product,
                'qty' => $cart[$product->getId()]['qty'] + 1
            ];
            // If the product is not in the cart,
            // Add it to the cart with a quantity (qty) of 1
        } else {
            $cart[$product->getId()] = [
                'object' => $product,
                'qty' => 1
            ];
        }

        // Update the cart in the session
        $this->requestStack->getSession()->set('cart', $cart);
    }

    public function decrease($id)
    {
        $cart = $this->requestStack->getSession()->get('cart');
        if ($cart[$id]['qty'] > 1) {
            $cart[$id]['qty']--;
        } else {
            unset($cart[$id]);
        }
        $this->requestStack->getSession()->set('cart', $cart);
    }

    // Retrieves the current cart from the session
    public function getCart()
    {
        return $this->requestStack->getSession()->get('cart');
    }

    public function remove()
    {
        return $this->requestStack->getSession()->remove('cart');
    }

    public function fullQantity()
    {
        $cart = $this->requestStack->getSession()->get('cart');
        $fullQantity = 0;
        if (!isset($cart)) {
            return $fullQantity;
        }
        foreach ($cart as $item) {
            $fullQantity += $item['qty'];
        }
        return $fullQantity;
    }

    public function getTotalWt()
    {
        $cart = $this->requestStack->getSession()->get('cart');
        $totalWt = 0;
        if (!isset($cart)) {
            return $totalWt;
        }
        foreach ($cart as $item) {
            $totalWt += $item['object']->getPriceWt() * $item['qty'];
        }
        return $totalWt;
    }

}
