<?php

/**
 * UserCrudController
 *
 * This file defines the UserCrudController,
 * which manages the CRUD operations for the
 * User entity in the EasyAdmin interface.
 *
 * @category Controllers
 * @package  App\Controller\Admin
 * @author   Maher Ben Rhouma <maherbenrhoumaaa@gmail.com>
 * @license  No license (Personal project)
Maher Ben Rhouma <maherbenrhoumaaa@gmail.com>
 * @link     https://symfony.com/doc/current/bundles/EasyAdminBundle/index.html
 */

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

/**
 * UserCrudController
 *
 * Manages the CRUD operations for the User entity.
 *
 * @category Controllers
 * @package  App\Controller\Admin
 */
class UserCrudController extends AbstractCrudController
{
    /**
     * Returns the fully qualified class name of the User entity.
     *
     * @return string The class name of the User entity.
     */
    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    /**
     * Configures CRUD settings for the User entity.
     *
     * @param Crud $crud The CRUD configuration object.
     *
     * @return Crud The configured CRUD object.
     */
    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Users')
            ->setEntityLabelInPlural('Users');
    }

    /**
     * Configures the fields displayed in the CRUD interface
     * for the User entity.
     *
     * @param string $pageName The name of the CRUD page (e.g., index, edit).
     *
     * @return iterable The list of configured fields.
     */
    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('firstname')->setLabel('First Name'),
            TextField::new('lastname')->setLabel('Last Name'),
            DateField::new('lastLoginAt')->setLabel('Last login')->onlyOnIndex(),
            ChoiceField::new('roles')
                ->setLabel('Permissions')
                ->setHelp('Permissions of the user')
                ->setChoices(
                    [
                    'ROLE_USER' => 'ROLE_USER',
                    'ROLE_ADMIN' => 'ROLE_ADMIN',
                    ]
                )
                ->allowMultipleChoices(),
            TextField::new('email')->setLabel('Email')->onlyOnIndex(),
        ];
    }
}
