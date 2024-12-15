<?php

/**
 * IndexController
 *
 * This file defines the IndexController, which manages the user's
 * account dashboard.
 * It retrieves and displays the user's orders in specific states.
 *
 * @category Controllers
 * @package  App\Controller\Account
 * @author   Maher Ben Rhouma <maherbenrhoumaaa@gmail.com>
 * @license  No license (Personal project)
Maher Ben Rhouma <maherbenrhoumaaa@gmail.com>
 * @link     https://symfony.com/doc/current/controller.html
 */

namespace App\Controller\Account;

use App\Repository\OrderRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * IndexController
 *
 * Manages the user's account dashboard.
 *
 * @category Controllers
 * @package  App\Controller\Account
 */
class IndexController extends AbstractController
{
    /**
     * Displays the user's account dashboard.
     *
     * @param OrderRepository $orderRepository The repository for fetching order data.
     *
     * @return Response Renders the user's account dashboard with their orders.
     */
    #[Route('/account', name: 'app_account')]
    public function index(OrderRepository $orderRepository): Response
    {
        $orders = $orderRepository->findBy(
            [
                'user' => $this->getUser(),
                'state' => [2, 3],
            ]
        );

        return $this->render(
            'account/index.html.twig',
            [
                'orders' => $orders,
            ]
        );
    }
}
