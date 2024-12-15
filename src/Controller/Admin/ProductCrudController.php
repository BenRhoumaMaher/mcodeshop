<?php

/**
 * ProductCrudController
 *
 * This file defines the ProductCrudController,
 * which manages the CRUD operations for the Product entity
 * in the EasyAdmin interface.
 *
 * @category Controllers
 * @package  App\Controller\Admin
 * @author   Maher Ben Rhouma <maherbenrhoumaaa@gmail.com>
 * @license  No license (Personal project)
Maher Ben Rhouma <maherbenrhoumaaa@gmail.com>
 * @link     https://symfony.com/doc/current/bundles/EasyAdminBundle/index.html
 */

namespace App\Controller\Admin;

use App\Entity\Product;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

/**
 * ProductCrudController
 *
 * Manages the CRUD operations for the Product entity.
 *
 * @category Controllers
 * @package  App\Controller\Admin
 */
class ProductCrudController extends AbstractCrudController
{
    /**
     * Returns the fully qualified class name of the Product entity.
     *
     * @return string The class name of the Product entity.
     */
    public static function getEntityFqcn(): string
    {
        return Product::class;
    }

    /**
     * Configures CRUD settings for the Product entity.
     *
     * @param Crud $crud The CRUD configuration object.
     *
     * @return Crud The configured CRUD object.
     */
    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Products')
            ->setEntityLabelInPlural('Products');
    }

    /**
     * Configures the fields displayed in the
     * CRUD interface for the Product entity.
     *
     * @param string $pageName The name of the CRUD page (e.g., index, edit).
     *
     * @return iterable The list of configured fields.
     */
    public function configureFields(string $pageName): iterable
    {
        $required = true;
        if ($pageName === 'edit') {
            $required = false;
        }

        return [
            TextField::new('name')->setLabel('Title')
                ->setHelp('Name of the product'),
            BooleanField::new('isHomepage')->setLabel('Homepages Product')
                ->setHelp('You can show a product on the homepage'),
            SlugField::new('slug')->setTargetFieldName('name')
                ->setLabel('URL')
                ->setHelp('Slug of the product'),
            TextEditorField::new('description')->setLabel('Description')
                ->setHelp('Description of the product'),
            ImageField::new('illustration')->setLabel('Image')
                ->setHelp('Image of the product')
                ->setUploadedFileNamePattern(
                    '[year]-[month]-[day]-[contenthash].[extension]'
                )
                ->setBasePath('uploads/')
                ->setUploadDir('public/uploads')
                ->setRequired($required),
            NumberField::new('price')->setLabel('Price')
                ->setHelp('Price of the product'),
            ChoiceField::new('tva')
                ->setLabel('TVA')
                ->setChoices(
                    [
                    '20%' => '20',
                    '10%' => '10',
                    '5.5%' => '5.5',
                    ]
                ),
            AssociationField::new('category')->setLabel('Category')
                ->setHelp('Category of the product'),
        ];
    }
}
