<?php

/**
 * PaymentController
 *
 * This file defines the PaymentController,
 * responsible for managing the payment process using Stripe.
 *
 * @category Controllers
 * @package  App\Controller\Payment
 * @author   Maher Ben Rhouma <maherbenrhoumaaa@gmail.com>
 * @license  No license (Personal project)
 * @link     https://symfony.com/doc/current/controller.html
 */

namespace App\Controller;

use Stripe\Stripe;
use App\Classe\Cart;
use Stripe\Checkout\Session;
use App\Repository\OrderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * PaymentController
 *
 * Handles payment-related functionality,
 * such as initiating and completing Stripe payments.
 *
 * @category Controllers
 * @package  App\Controller\Payment
 */
class PaymentController extends AbstractController
{
    /**
     * Initiates the payment process for an order.
     *
     * @param OrderRepository        $orderRepository The repository for fetching order data.
     * @param int                    $id_order        The ID of the order to process payment for.
     * @param EntityManagerInterface $entityManager   The entity manager for saving payment session data.
     *
     * @return Response Redirects to the Stripe payment session URL.
     */
    #[Route('/order/payment/{id_order}', name: 'app_payment')]
    public function index(
        OrderRepository $orderRepository,
        int $id_order,
        EntityManagerInterface $entityManager
    ): Response {
        Stripe::setApiKey($_ENV['STRIPE_SECRET_KEY']);

        $order = $orderRepository->findOneBy(
            [
            'id' => $id_order,
            'user' => $this->getUser()
            ]
        );

        $products_for_stripe = [];
        foreach ($order->getOrderDetails() as $product) {
            $products_for_stripe[] = [
                'price_data' => [
                    'currency' => 'usd',
                    'unit_amount' => number_format(
                        $product->getProductPriceWt() * 100,
                        0,
                        '',
                        ''
                    ),
                    'product_data' => [
                        'name' => $product->getProductName(),
                        'images' => [$_ENV['DOMAIN'] . '/uploads/' .
                        $product->getProductIllustration()]
                    ]
                ],
                'quantity' => $product->getProductQuantity(),
            ];
        }

        $products_for_stripe[] = [
            'price_data' => [
                'currency' => 'usd',
                'unit_amount' => number_format(
                    $order->getCarrierPrice() * 100,
                    0,
                    '',
                    ''
                ),
                'product_data' => [
                    'name' => 'Carrier: ' . $order->getCarrierName(),
                ]
            ],
            'quantity' => 1,
        ];

        $checkout_session = Session::create(
            [
            'customer_email' => $this->getUser()->getEmail(),
            'line_items' => $products_for_stripe,
            'mode' => 'payment',
            'success_url' => $_ENV['DOMAIN'] . '/order/thanks/{CHECKOUT_SESSION_ID}',
            'cancel_url' => $_ENV['DOMAIN'] . '/cart/annulation',
            ]
        );

        $order->setStripeSessionId($checkout_session->id);
        $entityManager->flush();

        return $this->redirect($checkout_session->url);
    }

    /**
     * Handles successful payments and updates the order state.
     *
     * @param string                 $stripe_session_id The Stripe session ID for the completed payment.
     * @param OrderRepository        $orderRepository   The repository for fetching order data.
     * @param EntityManagerInterface $entityManager     The entity manager for updating order data.
     * @param Cart                   $cart              The cart to clear upon successful payment.
     *
     * @return Response Renders the success page with order details.
     */
    #[Route('/order/thanks/{stripe_session_id}', name: 'app_payment_success')]
    public function success(
        string $stripe_session_id,
        OrderRepository $orderRepository,
        EntityManagerInterface $entityManager,
        Cart $cart
    ): Response {
        $order = $orderRepository->findOneBy(
            [
            'stripe_session_id' => $stripe_session_id,
            'user' => $this->getUser()
            ]
        );

        if (!$order) {
            return $this->redirectToRoute('app_home');
        }

        if ($order->getState() == 1) {
            $order->setState(2);
            $cart->remove();
            $entityManager->flush();
        }

        return $this->render(
            'payment/success.html.twig',
            [
                'order' => $order
            ]
        );
    }
}
