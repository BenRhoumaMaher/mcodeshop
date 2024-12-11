<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CategoryController extends AbstractController
{
    public function __construct(
        private CategoryRepository $categoryRepository
    ) {
    }
    #[Route('/category/{slug}', name: 'app_category')]
    public function index($slug): Response
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
