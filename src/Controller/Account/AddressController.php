<?php

namespace App\Controller\Account;

use App\Classe\Cart;
use App\Entity\Address;
use App\Form\AddressUserType;
use App\Repository\AddressRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AddressController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private AddressRepository $addressRepository
    ) {
    }

    #[Route(
        '/account/addresses',
        name: 'app_account_address'
    )]
    public function index(): Response
    {
        return $this->render('account/address/index.html.twig');
    }

    #[Route(
        '/account/addresses/delete/{id}',
        name: 'app_account_address_delete'
    )]
    public function delete($id): Response
    {
        $address = $this->addressRepository->findOneById($id);
        if (!$address || $address->getUser() != $this->getUser()) {
            return $this->redirectToRoute('app_account_address');
        }
        $this->entityManager->remove($address);
        $this->entityManager->flush();
        $this->addFlash(
            'success',
            'your address has been deleted'
        );
        return $this->redirectToRoute('app_account_address');
    }

    #[Route(
        '/account/addresses/add/{id}',
        name: 'app_account_address_form',
        defaults: ['id' => null]
    )]
    public function form(Request $request, $id, Cart $cart): Response
    {
        if ($id) {
            $address = $this->addressRepository->findOneById($id);
            if (!$address || $address->getUser() !== $this->getUser()) {
                return $this->redirectToRoute('app_account_address');
            }
        } else {
            $address = new Address();
            $address->setUser($this->getUser());
        }
        $form = $this->createForm(AddressUserType::class, $address);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($address);
            $this->entityManager->flush();

            $this->addFlash(
                'success',
                'your address has been updated'
            );
            if ($cart->fullQantity() > 0) {
                return $this->redirectToRoute('app_order');
            }
            return $this->redirectToRoute('app_account_address');
        }
        return $this->render(
            'account/address/form.html.twig',
            [
            'addressForm' => $form
            ]
        );
    }
}
