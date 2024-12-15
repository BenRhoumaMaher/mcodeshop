<?php

/**
 * AddressController
 *
 * This file defines the AddressController, which manages user
 * addresses in the account section.
 * It provides functionality to list, add, update, and delete addresses.
 *
 * @category Controllers
 * @package  App\Controller\Account
 * @author   Maher Ben Rhouma <maherbenrhoumaaa@gmail.com>
 * @license  No license (Personal project)
Maher Ben Rhouma <maherbenrhoumaaa@gmail.com>
 * @link     https://symfony.com/doc/current/controller.html
 */

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

/**
 * AddressController
 *
 * Manages user addresses in the account section.
 *
 * @category Controllers
 * @package  App\Controller\Account
 */
class AddressController extends AbstractController
{
    /**
     * Constructor
     *
     * @param EntityManagerInterface $entityManager     The entity manager for database operations.
     * @param AddressRepository      $addressRepository The repository for fetching address data.
     */
    public function __construct(
        private EntityManagerInterface $entityManager,
        private AddressRepository $addressRepository
    ) {
    }

    /**
     * Displays the list of user addresses.
     *
     * @return Response Renders the address list view.
     */
    #[Route('/account/addresses', name: 'app_account_address')]
    public function index(): Response
    {
        return $this->render('account/address/index.html.twig');
    }

    /**
     * Deletes a specific user address.
     *
     * @param int $id The ID of the address to delete.
     *
     * @return Response Redirects to the address list page.
     */
    #[Route('/account/addresses/delete/{id}', name: 'app_account_address_delete')]
    public function delete($id): Response
    {
        $address = $this->addressRepository->findOneById($id);

        if (!$address || $address->getUser() != $this->getUser()) {
            return $this->redirectToRoute('app_account_address');
        }

        $this->entityManager->remove($address);
        $this->entityManager->flush();

        $this->addFlash('success', 'Your address has been deleted.');

        return $this->redirectToRoute('app_account_address');
    }

    /**
     * Displays and processes the address form for adding or updating addresses.
     *
     * @param Request $request The HTTP request containing form data.
     * @param int     $id      The ID of the address to edit (optional).
     * @param Cart    $cart    The shopping cart to check if it contains items.
     *
     * @return Response Renders the form or redirects to the appropriate page.
     */
    #[Route('/account/addresses/add/{id}', name: 'app_account_address_form', defaults: ['id' => null])]
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

            $this->addFlash('success', 'Your address has been updated.');

            if ($cart->fullQantity() > 0) {
                return $this->redirectToRoute('app_order');
            }

            return $this->redirectToRoute('app_account_address');
        }

        return $this->render(
            'account/address/form.html.twig',
            [
                'addressForm' => $form,
            ]
        );
    }
}
