<?php

namespace App\Controller;

use App\Repository\OrderRepository;
use Dompdf\Dompdf;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class InvoiceController extends AbstractController
{
    #[Route('/account/invoice/impression/{id_order}', name: 'app_invoice_customer')]
    public function printforcustomer(
        $id_order,
        OrderRepository $orderRepository
    ): Response {
        $order = $orderRepository->findOneById($id_order);
        if (!$order) {
            return $this->redirectToRoute('app_account');
        }
        if ($order->getUser() != $this->getUser()) {
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
            'Attachement' => false
             ]
        );
        exit();
    }

    #[Route('/admin/invoice/impression/{id_order}', name: 'app_invoice_admin')]
    public function printforadmin(
        $id_order,
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
            'Attachement' => false
             ]
        );
        exit();
    }
}
