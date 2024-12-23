<?php

/**
 * DashboardController
 *
 * This file defines the DashboardController,
 * which manages the main administration dashboard.
 * It handles routing to CRUD controllers and the
 * menu configuration for the admin interface.
 *
 * @category Controllers
 * @package  App\Controller\Admin
 * @author   Maher Ben Rhouma <maherbenrhoumaaa@gmail.com>
 * @license  No license (Personal project)
 * @link     https://symfony.com/doc/current/bundles/EasyAdminBundle/index.html
 */

namespace App\Controller\Admin;

use App\Controller\Admin\UserCrudController;
use App\Entity\Carrier;
use App\Entity\Category;
use App\Entity\Header;
use App\Entity\Order;
use App\Entity\Product;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;

/**
 * DashboardController
 *
 * Handles the EasyAdmin dashboard and routes to specific CRUD controllers.
 *
 * @category Controllers
 * @package  App\Controller\Admin
 */
class DashboardController extends AbstractDashboardController
{
    /**
     * Redirects to the User CRUD controller on dashboard access.
     *
     * @return Response Redirects to the admin user management page.
     */
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);

        return $this->redirect(
            $adminUrlGenerator
                ->setController(UserCrudController::class)->generateUrl()
        );
    }

    /**
     * Configures the dashboard title.
     *
     * @return Dashboard The configured dashboard.
     */
    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Mcodeshop');
    }

    /**
     * Configures the menu items in the admin panel.
     *
     * @return iterable The list of menu items.
     */
    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToCrud('Users', 'fas fa-list', User::class);
        yield MenuItem::linkToCrud('Categories', 'fas fa-list', Category::class);
        yield MenuItem::linkToCrud('Products', 'fas fa-list', Product::class);
        yield MenuItem::linkToCrud('Carriers', 'fas fa-list', Carrier::class);
        yield MenuItem::linkToCrud('Orders', 'fas fa-list', Order::class);
        yield MenuItem::linkToCrud('Header', 'fas fa-list', Header::class);
    }
}
