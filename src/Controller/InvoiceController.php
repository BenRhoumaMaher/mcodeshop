<?php

/**
 * InvoiceController
 *
 * This file defines the InvoiceController,
 * responsible for handling invoice generation
 * and rendering for customers and administrators.
 *
 * @category Controllers
 * @package  App\Controller\Invoice
 * @author   Maher Ben Rhouma <maherbenrhoumaaa@gmail.com>
 * @license  No license (Personal project)
Maher Ben Rhouma <maherbenrhoumaaa@gmail.com>
 * @link     https://symfony.com/doc/current/controller.html
 */

namespace App\Controller;

use App\Repository\OrderRepository;
use Dompdf\Dompdf;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * InvoiceController
 *
 * Manages invoice rendering and printing for customers and administrators.
 *
 * @category Controllers
 * @package  App\Controller\Invoice
 */
class InvoiceController extends AbstractController
{
    /**
     * Generates and renders an invoice for the customer.
     *
     * @param int             $id_order       The ID of the order for which to generate the invoice.
     * @param OrderRepository $orderRepository The repository for fetching order data.
     *
     * @return Response Renders or downloads the invoice PDF.
     */
    #[Route('/account/invoice/impression/{id_order}', name: 'app_invoice_customer')]
    public function printforcustomer(
        int $id_order,
        OrderRepository $orderRepository
    ): Response {
        $order = $orderRepository->findOneById($id_order);

        if (!$order || $order->getUser() != $this->getUser()) {
            return $this->redirectToRoute('app_account');
        }

        $dompdf = new Dompdf();
        $html = $this->renderView(
            'invoice/index.html.twig',
            [
                'order' => $order
            ]
        );
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();
        $dompdf->stream(
            'invoice.pdf',
            [
                'Attachment' => false
            ]
        );

        exit();
    }

    /**
     * Generates and renders an invoice for the administrator.
     *
     * @param int             $id_order       The ID of the order for which to generate the invoice.
     * @param OrderRepository $orderRepository The repository for fetching order data.
     *
     * @return Response Renders or downloads the invoice PDF.
     */
    #[Route('/admin/invoice/impression/{id_order}', name: 'app_invoice_admin')]
    public function printforadmin(
        int $id_order,
        OrderRepository $orderRepository
    ): Response {
        $order = $orderRepository->findOneById($id_order);

        if (!$order) {
            return $this->redirectToRoute('admin');
        }

        $dompdf = new Dompdf();
        $html = $this->renderView(
            'invoice/index.html.twig',
            [
                'order' => $order
            ]
        );
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();
        $dompdf->stream(
            'invoice.pdf',
            [
                'Attachment' => false
            ]
        );

        exit();
    }
}
