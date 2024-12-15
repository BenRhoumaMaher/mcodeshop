<?php

/**
 * WishListController
 *
 * This file defines the WishListController,
 * which manages the user's wishlist functionality in their account section.
 *
 * @category Controllers
 * @package  App\Controller\Account
 * @author   Maher Ben Rhouma <maherbenrhoumaaa@gmail.com>
 * @license  No license (Personal project)
Maher Ben Rhouma <maherbenrhoumaaa@gmail.com>
 * @link     https://symfony.com/doc/current/controller.html
 */

namespace App\Controller\Account;

use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * WishListController
 *
 * Manages the user's wishlist functionality.
 *
 * @category Controllers
 * @package  App\Controller\Account
 */
class WishListController extends AbstractController
{
    /**
     * Displays the user's wishlist.
     *
     * @return Response Renders the wishlist view.
     */
    #[Route('/account/wishlist', name: 'app_account_wishlist')]
    public function index(): Response
    {
        return $this->render('account/wish_list/index.html.twig');
    }

    /**
     * Adds a product to the user's wishlist.
     *
     * @param ProductRepository      $productRepository The repository for fetching product data.
     * @param EntityManagerInterface $entityManager     The entity manager for database operations.
     * @param Request                $request           The HTTP request for redirecting back.
     * @param int                    $id                The ID of the product to add.
     *
     * @return Response Redirects back to the previous page with a success message.
     */
    #[Route('/account/wishlist/add/{id}', name: 'app_account_wishlist_add')]
    public function add(
        ProductRepository $productRepository,
        EntityManagerInterface $entityManager,
        Request $request,
        $id
    ): Response {
        $product = $productRepository->findOneById($id);

        if ($product) {
            $this->getUser()->addWishList($product);
            $entityManager->flush();

            $this->addFlash(
                'success',
                'Product has been added to your wishlist'
            );
        }

        return $this->redirect(
            $request->headers->get('referer')
        );
    }

    /**
     * Removes a product from the user's wishlist.
     *
     * @param ProductRepository      $productRepository The repository for fetching product data.
     * @param EntityManagerInterface $entityManager     The entity manager for database operations.
     * @param Request                $request           The HTTP request for redirecting back.
     * @param int                    $id                The ID of the product to remove.
     *
     * @return Response Redirects back to the previous page with a success or error message.
     */
    #[Route('/account/wishlist/remove/{id}', name: 'app_account_wishlist_remove')]
    public function remove(
        ProductRepository $productRepository,
        EntityManagerInterface $entityManager,
        Request $request,
        $id
    ): Response {
        $product = $productRepository->findOneById($id);

        if ($product) {
            $this->getUser()->removeWishList($product);
            $entityManager->flush();

            $this->addFlash(
                'success',
                'Product has been removed from your wishlist'
            );
        } else {
            $this->addFlash(
                'danger',
                'Product not found'
            );
        }

        return $this->redirect(
            $request->headers->get('referer')
        );
    }
}
