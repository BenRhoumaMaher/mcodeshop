<?php

/**
 * Cart
 *
 * This class manages the shopping cart functionality,
 * including adding, removing, decreasing quantities, and calculating totals.
 *
 * @category Classes
 * @package  App\Classe
 * @author   Maher Ben Rhouma <maherbenrhoumaaa@gmail.com>
 * @license  No license (Personal project)
Maher Ben Rhouma <maherbenrhoumaaa@gmail.com>
 * @link     https://symfony.com/doc/current/session.html
 */

namespace App\Classe;

use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Cart
 *
 * Manages the shopping cart functionality.
 *
 * @category Classes
 * @package  App\Classe
 */
class Cart
{
    /**
     * Constructor
     *
     * @param RequestStack $requestStack Provides access to the session for cart storage.
     */
    public function __construct(private RequestStack $requestStack)
    {
    }

    /**
     * Adds a product to the cart.
     *
     * @param object $product The product to add.
     */
    public function add($product)
    {
        $cart = $this->requestStack->getSession()->get('cart');

        if (isset($cart[$product->getId()])) {
            $cart[$product->getId()] = [
                'object' => $product,
                'qty' => $cart[$product->getId()]['qty'] + 1,
            ];
        } else {
            $cart[$product->getId()] = [
                'object' => $product,
                'qty' => 1,
            ];
        }

        $this->requestStack->getSession()->set('cart', $cart);
    }

    /**
     * Decreases the quantity of a product in the cart or removes it if the quantity is 1.
     *
     * @param int $id The ID of the product to decrease.
     */
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

    /**
     * Retrieves the current cart from the session.
     *
     * @return array|null The cart data.
     */
    public function getCart()
    {
        return $this->requestStack->getSession()->get('cart');
    }

    /**
     * Removes the entire cart from the session.
     *
     * @return void
     */
    public function remove()
    {
        $this->requestStack->getSession()->remove('cart');
    }

    /**
     * Calculates the total quantity of products in the cart.
     *
     * @return int The total quantity of products.
     */
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

    /**
     * Calculates the total price (including tax) of all products in the cart.
     *
     * @return float The total price with tax.
     */
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
