<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ProductController extends AbstractController
{
    public function __construct(
        private ProductRepository $productRepository
    ) {

    }
    #[Route('/product/{slug}', name: 'app_product')]
    public function index($slug): Response
    {
        $product = $this->productRepository->findOneBySlug($slug);
        if (!$product) {
            return $this->redirectToRoute('app_home');
        }
        return $this->render(
            'product/index.html.twig',
            [
            'product' => $product,
            ]
        );
    }
}
