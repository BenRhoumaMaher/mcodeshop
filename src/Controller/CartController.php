<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CartController extends AbstractController
{
    public function __construct(
        private Cart $cart,
        private ProductRepository $productRepository
    ) {
    }
    #[Route('/cart', name: 'app_cart')]
    public function index(): Response
    {
        return $this->render(
            'cart/index.html.twig',
            [
            'cart' => $this->cart->getCart(),
            'totalwt' => $this->cart->getTotalWt(),
            ]
        );
    }

    #[Route('/cart/add/{id}', name: 'app_cart_add')]
    public function add($id, Request $request): Response
    {
        $product = $this->productRepository->find($id);
        $this->cart->add($product);
        $this->addFlash(
            'success',
            'Product has been added to your basket'
        );
        return $this->redirect(
            $request->headers->get('referer')
        );
    }

    #[Route('/cart/decrease/{id}', name: 'app_cart_decrease')]
    public function decrease($id): Response
    {
        $this->cart->decrease($id);
        $this->addFlash(
            'success',
            'product has been deleted',
        );
        return $this->redirectToRoute('app_cart');
    }

    #[Route('/cart/remove', name: 'app_cart_remove')]
    public function remove(): Response
    {
        $this->cart->remove();
        return $this->redirectToRoute('app_home');
    }

}
