<?php

/**
 * OrderCrudController
 *
 * This file defines the OrderCrudController,
 * which manages the CRUD operations for
 * the Order entity in the EasyAdmin interface.
 * It also provides custom actions for updating
 * order states and sending notifications.
 *
 * @category Controllers
 * @package  App\Controller\Admin
 * @author   Maher Ben Rhouma <maherbenrhoumaaa@gmail.com>
 * @license  No license (Personal project)
 * @link     https://symfony.com/doc/current/bundles/EasyAdminBundle/index.html
 */

namespace App\Controller\Admin;

use Src\Classe\Mail;
use App\Classe\State;
use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

/**
 * OrderCrudController
 *
 * Manages the CRUD operations and custom actions for the Order entity.
 *
 * @category Controllers
 * @package  App\Controller\Admin
 */
class OrderCrudController extends AbstractCrudController
{
    /**
     * Constructor
     *
     * @param EntityManagerInterface $entityManager Manages database operations.
     */
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    /**
     * Returns the fully qualified class name of the Order entity.
     *
     * @return string The class name of the Order entity.
     */
    public static function getEntityFqcn(): string
    {
        return Order::class;
    }

    /**
     * Configures CRUD settings for the Order entity.
     *
     * @param Crud $crud The CRUD configuration object.
     *
     * @return Crud The configured CRUD object.
     */
    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Order')
            ->setEntityLabelInPlural('Orders');
    }

    /**
     * Configures the actions available in the CRUD interface for the Order entity.
     *
     * @param Actions $actions The actions configuration object.
     *
     * @return Actions The configured actions object.
     */
    public function configureActions(Actions $actions): Actions
    {
        $show = Action::new('show')->linkToCrudAction('show');

        return $actions
            ->add(Crud::PAGE_INDEX, $show)
            ->remove(Crud::PAGE_INDEX, Action::NEW)
            ->remove(Crud::PAGE_INDEX, Action::EDIT)
            ->remove(Crud::PAGE_INDEX, Action::DELETE);
    }

    /**
     * Changes the state of an order and sends a notification email to the user.
     *
     * @param Order $order The order to update.
     * @param int   $state The new state of the order.
     *
     * @return array Contains the updated state.
     */
    public function changeState($order, $state)
    {
        $order->setState($state);
        $this->entityManager->flush();

        $this->addFlash('success', 'Order Status updated successfully');

        $mail = new Mail();
        $vars = [
            'firstname' => $order->getUser()->getFirstName(),
            'id_order' => $order->getId(),
        ];
        $mail->send(
            $order->getUser()->getEmail(),
            $order->getUser()->getFirstName()
            . ' ' . $order->getUser()->getLastName(),
            State::STATE[$state]['mail_subject'],
            State::STATE[$state]['mail_template'],
            $vars
        );

        return ['state' => $state];
    }

    /**
     * Displays the details of an order and handles state changes.
     *
     * @param AdminContext     $context          The current admin context.
     * @param AdminUrlGenerator $adminUrlGenerator Generates URLs for CRUD actions.
     * @param Request          $request          The current HTTP request.
     *
     * @return Response         Renders the order details page.
     */
    public function show(
        AdminContext $context,
        AdminUrlGenerator $adminUrlGenerator,
        Request $request
    ) {
        $order = $context->getEntity()->getInstance();

        $url = $adminUrlGenerator
            ->setController(self::class)
            ->setAction('show')
            ->setEntityId($order->getId())
            ->generateUrl();

        if ($request->get('state')) {
            $this->changeState($order, $request->get('state'));
        }

        return $this->render(
            'admin/order.html.twig',
            [
                'order' => $order,
                'current_url' => $url,
            ]
        );
    }

    /**
     * Configures the fields displayed in the CRUD
     * interface for the Order entity.
     *
     * @param string $pageName The name of the CRUD page (e.g., index, edit).
     *
     * @return iterable The list of configured fields.
     */
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            DateField::new('createdAt')->setLabel('Date'),
            NumberField::new('state')->setLabel('Statut')
                ->setTemplatePath('admin/state.html.twig'),
            AssociationField::new('user')->setLabel('User'),
            TextField::new('carrierName')->setLabel('Carrier'),
            NumberField::new('totalTva')->setLabel('Total TVA'),
            NumberField::new('totalWt')->setLabel('Total TTC'),
        ];
    }
}
