<?php

/**
 * CartController
 *
 * This file defines the CartController,
 * such as adding, removing, and displaying cart items.
 * responsible for managing the shopping cart functionality,
 *
 * @category Controllers
 * @package  App\Controller\Cart
 * @author   Maher Ben Rhouma <maherbenrhoumaaa@gmail.com>
 * @license  No license (Personal project)
 * @link     https://symfony.com/doc/current/controller.html
 */

namespace App\Controller;

use App\Classe\Cart;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * CartController
 *
 * Manages the shopping cart, including item addition,
 * removal, and displaying the cart summary.
 *
 * @category Controllers
 * @package  App\Controller\Cart
 */
class CartController extends AbstractController
{
    /**
     * Constructor
     *
     * @param Cart              $cart             The shopping cart service.
     * @param ProductRepository $productRepository The repository for fetching product data.
     */
    public function __construct(
        private Cart $cart,
        private ProductRepository $productRepository
    ) {
    }

    /**
     * Displays the shopping cart.
     *
     * @param string|null $motif An optional reason for the cart action (e.g., cancellation).
     *
     * @return Response Renders the cart view with its contents and total price.
     */
    #[Route('/cart/{motif}', name: 'app_cart', defaults: ['motif' => null])]
    public function index(?string $motif): Response
    {
        if ($motif === "annulation") {
            $this->addFlash('info', 'Payment canceled');
        }

        return $this->render(
            'cart/index.html.twig',
            [
                'cart' => $this->cart->getCart(),
                'totalwt' => $this->cart->getTotalWt(),
            ]
        );
    }

    /**
     * Adds a product to the shopping cart.
     *
     * @param int     $id      The ID of the product to add.
     * @param Request $request The HTTP request to redirect back to the previous page.
     *
     * @return Response Redirects back to the previous page with a success flash message.
     */
    #[Route('/cart/add/{id}', name: 'app_cart_add')]
    public function add(int $id, Request $request): Response
    {
        $product = $this->productRepository->find($id);
        $this->cart->add($product);

        $this->addFlash('success', 'Product has been added to your basket');

        return $this->redirect($request->headers->get('referer'));
    }

    /**
     * Decreases the quantity of a product in the shopping cart or removes it.
     *
     * @param int $id The ID of the product to decrease.
     *
     * @return Response Redirects to the cart page.
     */
    #[Route('/cart/decrease/{id}', name: 'app_cart_decrease')]
    public function decrease(int $id): Response
    {
        $this->cart->decrease($id);
        $this->addFlash('success', 'Product has been removed');

        return $this->redirectToRoute('app_cart');
    }

    /**
     * Clears the shopping cart.
     *
     * @return Response Redirects to the homepage after clearing the cart.
     */
    #[Route('/cart/remove', name: 'app_cart_remove')]
    public function remove(): Response
    {
        $this->cart->remove();

        return $this->redirectToRoute('app_home');
    }
}
