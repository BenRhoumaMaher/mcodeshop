<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProductController extends AbstractController
{
    public function __construct(
        private ProductRepository $productRepository
    ) {

    }
    #[Route('/product/{slug}', name: 'app_product')]
    public function index(#[MapEntity(mapping: ['slug' => 'slug'])] Product $product): Response
    {
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
