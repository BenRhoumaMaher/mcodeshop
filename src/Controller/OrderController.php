<?php

/**
 * OrderController
 *
 * This file defines the OrderController,
 * responsible for managing the order process,
 * including address selection, order creation, and order summary.
 *
 * @category Controllers
 * @package  App\Controller\Order
 * @author   Maher Ben Rhouma <maherbenrhoumaaa@gmail.com>
 * @license  No license (Personal project)
 * @link     https://symfony.com/doc/current/controller.html
 */

namespace App\Controller;

use App\Classe\Cart;
use App\Entity\Order;
use App\Entity\OrderDetails;
use App\Form\OrderType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * OrderController
 *
 * Manages order-related functionality,
 * such as address selection, order creation,
 * and displaying order summaries.
 *
 * @category Controllers
 * @package  App\Controller\Order
 */
class OrderController extends AbstractController
{
    /**
     * Constructor
     *
     * @param EntityManagerInterface $entityManager Handles database operations.
     */
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    /**
     * Displays the delivery address selection form.
     *
     * @return Response Renders the delivery form view.
     */
    #[Route('/order/delivery', name: 'app_order')]
    public function index(): Response
    {
        $addresses = $this->getUser()->getAddresses();

        if (count($addresses) == 0) {
            return $this->redirectToRoute('app_account_address_form');
        }

        $form = $this->createForm(
            OrderType::class,
            null,
            [
                'addresses' => $this->getUser()->getAddresses(),
                'action' => $this->generateUrl('app_order_summary'),
            ]
        );

        return $this->render(
            'order/index.html.twig',
            [
                'deliveryForm' => $form->createView(),
            ]
        );
    }

    /**
     * Processes the order and displays the order summary.
     *
     * @param Request $request The HTTP request containing form data.
     * @param Cart    $cart    The cart containing selected products.
     *
     * @return Response Renders the order summary view.
     */
    #[Route('/order/summary', name: 'app_order_summary')]
    public function add(Request $request, Cart $cart): Response
    {
        if ($request->getMethod() != 'POST') {
            return $this->redirectToRoute('app_cart');
        }

        $products = $cart->getCart();
        $form = $this->createForm(
            OrderType::class,
            null,
            ['addresses' => $this->getUser()->getAddresses()]
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $addressObj = $form->get('addresses')->getData();
            $address = sprintf(
                "%s %s<br/>%s<br/>%s %s<br/>%s<br/>%s",
                $addressObj->getFirstname(),
                $addressObj->getLastname(),
                $addressObj->getAddress(),
                $addressObj->getPostal(),
                $addressObj->getCity(),
                $addressObj->getCountry(),
                $addressObj->getPhone()
            );

            $order = new Order();
            $order->setUser($this->getUser());
            $order->setCreatedAt(new \DateTime());
            $order->setState(1);
            $order->setCarrierName($form->get('carriers')->getData()->getName());
            $order->setCarrierPrice($form->get('carriers')->getData()->getPrice());
            $order->setDelivery($address);

            foreach ($products as $product) {
                $orderDetail = new OrderDetails();
                $orderDetail->setProductName($product['object']->getName());
                $orderDetail->setProductIllustration(
                    $product['object']->getIllustration()
                );
                $orderDetail->setProductPrice($product['object']->getPrice());
                $orderDetail->setProductTva($product['object']->getTva());
                $orderDetail->setProductQuantity($product['qty']);
                $order->addOrderDetail($orderDetail);
            }

            $this->entityManager->persist($order);
            $this->entityManager->flush();
        }

        return $this->render(
            'order/summary.html.twig',
            [
                'choices' => $form->getData(),
                'cart' => $products,
                'order' => $order,
                'totalWt' => $cart->getTotalWt(),
            ]
        );
    }
}
