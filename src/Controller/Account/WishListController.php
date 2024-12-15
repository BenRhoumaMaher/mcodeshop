<?php

namespace App\Controller\Account;

use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class WishListController extends AbstractController
{
    #[Route('/account/wishlist', name: 'app_account_wishlist')]
    public function index(): Response
    {
        return $this->render('account/wish_list/index.html.twig');
    }

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
        }
        $this->addFlash(
            'success',
            'Product has been added to your wishlist'
        );
        return $this->redirect(
            $request->headers->get('referer')
        );
    }

    #[Route('/account/wishlist/remove/{id}', name: 'app_account_wishlist_remove')]
    public function remove(
        ProductRepository $productRepository,
        EntityManagerInterface $entityManager,
        Request $request,
        $id
    ): Response {
        $product = $productRepository->findOneById($id);
        if ($product) {
            $this->addFlash(
                'success',
                'Product has been removed from your wishlist'
            );
            $this->getUser()->removeWishList($product);
            $entityManager->flush();
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
