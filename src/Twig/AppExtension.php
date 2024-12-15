<?php

/**
 * AppExtension
 *
 * This file defines a Twig extension that provides custom
 * filters and global variables for templates.
 * It includes a custom price formatting filter
 * and global access to all categories and the cart quantity.
 *
 * @category Twig Extensions
 * @package  App\Twig
 * @author   Maher Ben Rhouma <maherbenrhoumaaa@gmail.com>
 * @license  No license (Personal project)
Maher Ben Rhouma <maherbenrhoumaaa@gmail.com>
 * @link     https://twig.symfony.com/doc/3.x/advanced.html
 */

namespace App\Twig;

use App\Classe\Cart;
use Twig\TwigFilter;
use Twig\Extension\GlobalsInterface;
use Twig\Extension\AbstractExtension;
use App\Repository\CategoryRepository;

/**
 * AppExtension
 *
 * Provides custom Twig filters and global variables for templates.
 *
 * @category Twig Extensions
 * @package  App\Twig
 */
class AppExtension extends AbstractExtension implements GlobalsInterface
{
    /**
     * Constructor
     *
     * @param CategoryRepository $categoryRepository Provides access to category data.
     * @param Cart               $cart               Provides access to cart data.
     */
    public function __construct(
        private CategoryRepository $categoryRepository,
        private Cart $cart
    ) {
    }

    /**
     * Defines custom Twig filters for templates.
     *
     * @return array An array of TwigFilter objects.
     */
    public function getFilters()
    {
        return [
            new TwigFilter('price', [$this, 'formatPrice']),
        ];
    }

    /**
     * Formats a number as a price with two decimal places and a currency symbol.
     *
     * @param float $number The number to format.
     *
     * @return string The formatted price.
     */
    public function formatPrice($number): string
    {
        return number_format($number, 2, ',') . ' Dt';
    }

    /**
     * Defines global variables accessible in all Twig templates.
     *
     * @return array An associative array of global variables.
     */
    public function getGlobals(): array
    {
        return [
            'allCategories' => $this->categoryRepository->findAll(),
            'fullCartQuantity' => $this->cart->fullQantity(),
        ];
    }
}
