<?php

/**
 * OrderController
 *
 * This file defines the OrderController,
 * which manages the display of individual orders
 * in the user's account section.
 *
 * @category Controllers
 * @package  App\Controller\Account
 * @license  No license (Personal project)
 * author
Maher Ben Rhouma <maherbenrhoumaaa@gmail.com>
 * @link     https://symfony.com/doc/current/controller.html
 */

namespace App\Controller\Account;

use App\Repository\OrderRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * OrderController
 *
 * Manages the display of individual orders in the user's account section.
 *
 * @category Controllers
 * @package  App\Controller\Account
 */
class OrderController extends AbstractController
{
    /**
     * Displays a specific order in the user's account.
     *
     * @param int            $id_order       The ID of the order to display.
     * @param OrderRepository $orderRepository The repository for fetching order data.
     *
     * @return Response Renders the order view or redirects to the homepage if the order is not found.
     */
    #[Route('/account/order/{id_order}', name: 'app_account_order')]
    public function index(
        $id_order,
        OrderRepository $orderRepository
    ): Response {
        $order = $orderRepository->findOneBy(
            [
                'id' => $id_order,
                'user' => $this->getUser()
            ]
        );

        if (!$order) {
            return $this->redirectToRoute('app_home');
        }

        return $this->render(
            'account/order/index.html.twig',
            [
                'order' => $order,
            ]
        );
    }
}
