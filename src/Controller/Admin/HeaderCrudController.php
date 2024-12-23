<?php

/**
 * HeaderCrudController
 *
 * This file defines the HeaderCrudController,
 * which manages the CRUD operations for the Header
 * entity in the EasyAdmin interface.
 *
 * @category Controllers
 * @package  App\Controller\Admin
 * @author   Maher Ben Rhouma <maherbenrhoumaaa@gmail.com>
 * @license  No license (Personal project)
 * @link     https://symfony.com/doc/current/bundles/EasyAdminBundle/index.html
 */

namespace App\Controller\Admin;

use App\Entity\Header;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

/**
 * HeaderCrudController
 *
 * Manages the CRUD operations for the Header entity.
 *
 * @category Controllers
 * @package  App\Controller\Admin
 */
class HeaderCrudController extends AbstractCrudController
{
    /**
     * Returns the fully qualified class name of the Header entity.
     *
     * @return string The class name of the Header entity.
     */
    public static function getEntityFqcn(): string
    {
        return Header::class;
    }

    /**
     * Configures the fields displayed in the
     * CRUD interface for the Header entity.
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
            TextField::new('Title', 'Title'),
            TextareaField::new('content', 'Content'),
            TextField::new('buttonTitle', 'Title of the button'),
            TextField::new('buttonLink', 'URL of the button'),
            ImageField::new('illustration')->setLabel('Image of the header')
                ->setHelp('Image of the header in JPG')
                ->setUploadedFileNamePattern(
                    '[year]-[month]-[day]-[contenthash].[extension]'
                )
                ->setBasePath('uploads/')
                ->setUploadDir('public/uploads')
                ->setRequired($required),
        ];
    }
}
