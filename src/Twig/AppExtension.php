<?php

namespace App\Twig;

use App\Classe\Cart;
use Twig\TwigFilter;
use Twig\Extension\GlobalsInterface;
use Twig\Extension\AbstractExtension;
use App\Repository\CategoryRepository;

class AppExtension extends AbstractExtension implements GlobalsInterface
{
    public function __construct(
        private CategoryRepository $categoryRepository,
        private Cart $cart
    ) {
    }

    public function getFilters()
    {
        return [
            new TwigFilter('price', [$this, 'formatPrice']),
        ];
    }

    public function formatPrice($number): string
    {
        return number_format($number, 2, ',') . ' Dt';
    }

    public function getGlobals(): array
    {
        return [
            'allCategories' => $this->categoryRepository->findAll(),
            'fullCartQuantity' => $this->cart->fullQantity(),
        ];
    }

}
