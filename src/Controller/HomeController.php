<?php

/**
 * HomeController
 *
 * This file defines the HomeController, which is responsible for rendering the homepage.
 * It manages the retrieval of header information and products marked for display on the homepage.
 *
 * @category Controllers
 * @package  App\Controller\Home
 * @author   Maher Ben Rhouma <maherbenrhoumaaa@gmail.com>
 * @license  No license (Personal project)
 * @link     https://symfony.com/doc/current/controller.html
 */

namespace App\Controller;

use App\Repository\HeaderRepository;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * HomeController
 *
 * Handles the homepage functionality,
 * including fetching headers and products marked for display on the homepage.
 *
 * @category Controllers
 * @package  App\Controller\Home
 * @author   Maher Ben Rhouma <maherbenrhoumaaa@gmail.com>
 * @license  No license (Personal project)
 * @link     https://symfony.com/doc/current/controller.html
 */
class HomeController extends AbstractController
{
    /**
     * Displays the homepage with headers and products flagged for homepage display.
     *
     * @param HeaderRepository  $headerRepository  The repository used to fetch header data.
     * @param ProductRepository $productRepository The repository used to fetch product data.
     *
     * @return Response Renders the homepage view with headers and selected products.
     */
    #[Route('/', name: 'app_home')]
    public function index(
        HeaderRepository $headerRepository,
        ProductRepository $productRepository
    ): Response {
        return $this->render(
            'home/index.html.twig',
            [
                'headers' => $headerRepository->findAll(),
                'productsInHomepage' => $productRepository->findByIsHomepage(true),
            ]
        );
    }
}
