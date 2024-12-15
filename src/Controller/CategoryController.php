<?php

/**
 * CategoryController
 *
 * This file defines the CategoryController,
 * responsible for managing category details and rendering category pages.
 *
 * @category Controllers
 * @package  App\Controller\Category
 * @author   Maher Ben Rhouma <maherbenrhoumaaa@gmail.com>
 * @license  No license (Personal project)
 * @link     https://symfony.com/doc/current/controller.html
 */

namespace App\Controller;

use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * CategoryController
 *
 * Handles displaying category pages based on their slugs.
 *
 * @category Controllers
 * @package  App\Controller\Category
 * @link     https://symfony.com/doc/current/controller.html
 */
class CategoryController extends AbstractController
{
    /**
     * Constructor
     *
     * @param CategoryRepository $categoryRepository The repository
     *                                               for fetching category data.
     */
    public function __construct(
        private CategoryRepository $categoryRepository
    ) {
    }

    /**
     * Displays a category page based on its slug.
     *
     * @param string $slug The slug of the category.
     *
     * @return Response Renders the category page or
     * redirects to the homepage if the category is not found.
     */
    #[Route('/category/{slug}', name: 'app_category')]
    public function index(string $slug): Response
    {
        $category = $this->categoryRepository->findOneBySlug($slug);

        if (!$category) {
            return $this->redirectToRoute('app_home');
        }

        return $this->render(
            'category/index.html.twig',
            [
                'category' => $category,
            ]
        );
    }
}
