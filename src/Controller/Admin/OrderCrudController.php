<?php

namespace App\Controller\Admin;

use Src\Classe\Mail;
use App\Classe\State;
use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class OrderCrudController extends AbstractCrudController
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }
    public static function getEntityFqcn(): string
    {
        return Order::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Order')
            ->setEntityLabelInPlural('Orders')
        ;
    }

    public function configureActions(Actions $actions): Actions
    {
        $show = Action::new('show')->linkToCrudAction('show');
        return $actions
            ->add(Crud::PAGE_INDEX, $show)
            ->remove(Crud::PAGE_INDEX, Action::NEW)
            ->remove(Crud::PAGE_INDEX, Action::EDIT)
            ->remove(Crud::PAGE_INDEX, Action::DELETE)
        ;
    }

    public function changeState(
        $order,
        $state
    ) {
        $order->setState($state);
        $this->entityManager->flush();
        $this->addFlash(
            'success',
            'Order Status updated successfully'
        );
        $mail = new Mail();
        $vars = [
            'firstname' => $order->getUser()->getFirstName(),
            'id_order' => $order->getId()
        ];
        $mail->send(
            $order->getUser()->getEmail(),
            $order->getUser()->getFirstName(). ' ' .$order->getUser()->getLastName(),
            State::STATE[$state]['mail_subject'],
            State::STATE[$state]['mail_template'],
            $vars
        );
        return [
            'state' => $state
        ];
    }

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
            $this->changeState(
                $order,
                $request->get('state')
            );
        }
        return $this->render(
            'admin/order.html.twig',
            [
            'order' => $order,
            'current_url' => $url,
            ]
        );
    }

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
            NumberField::new('totalWt')->setLabel('Total TTC')
        ];
    }
}
