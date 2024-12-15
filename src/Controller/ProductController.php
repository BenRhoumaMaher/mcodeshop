<?php

/**
 * ProductController
 *
 * This file defines the ProductController,
 * responsible for managing product details and rendering specific product pages.
 *
 * @category Controllers
 * @package  App\Controller\Product
 * @author   Maher Ben Rhouma <maherbenrhoumaaa@gmail.com>
 * @license  No license (Personal project)
 * @link     https://symfony.com/doc/current/controller.html
 */

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * ProductController
 *
 * Handles displaying detailed product pages based on their slugs.
 *
 * @category Controllers
 * @package  App\Controller\Product
 * @link     https://symfony.com/doc/current/controller.html
 */
class ProductController extends AbstractController
{
    /**
     * Constructor
     *
     * @param ProductRepository $productRepository The repository for fetching product data.
     */
    public function __construct(
        private ProductRepository $productRepository
    ) {
    }

    /**
     * Displays a product page based on its slug.
     *
     * @param Product $product The product entity mapped via slug.
     *
     * @return Response Renders the product page or redirects to the homepage
     * if the product is not found.
     */
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
