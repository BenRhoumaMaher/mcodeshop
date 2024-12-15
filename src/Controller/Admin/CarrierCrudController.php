<?php

/**
 * CarrierCrudController
 *
 * This file defines the CarrierCrudController,
 * which manages the CRUD operations for the Carrier entity
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

use App\Entity\Carrier;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

/**
 * CarrierCrudController
 *
 * Manages the CRUD operations for the Carrier entity.
 *
 * @category Controllers
 * @package  App\Controller\Admin
 */
class CarrierCrudController extends AbstractCrudController
{
    /**
     * Returns the fully qualified class name of the Carrier entity.
     *
     * @return string The class name of the Carrier entity.
     */
    public static function getEntityFqcn(): string
    {
        return Carrier::class;
    }

    /**
     * Configures CRUD settings for the Carrier entity.
     *
     * @param Crud $crud The CRUD configuration object.
     *
     * @return Crud The configured CRUD object.
     */
    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Carriers')
            ->setEntityLabelInPlural('Carriers');
    }

    /**
     * Configures the fields displayed in the CRUD
     * interface for the Carrier entity.
     *
     * @param string $pageName The name of the CRUD page (e.g., index, edit).
     *
     * @return iterable The list of configured fields.
     */
    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name')->setLabel('Name of the carrier'),
            TextareaField::new('description')->setLabel('Description'),
            NumberField::new('price')->setLabel('Price')
                ->setHelp('Price of the carrier'),
        ];
    }
}
