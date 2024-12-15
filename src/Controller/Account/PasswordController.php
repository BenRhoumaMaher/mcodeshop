<?php

/**
 * PasswordController
 *
 * This file defines the PasswordController,
 * which manages password modification for users in their account section.
 *
 * @category Controllers
 * @package  App\Controller\Account
 * @author   Maher Ben Rhouma <maherbenrhoumaaa@gmail.com>
 * @license  No license (Personal project)
Maher Ben Rhouma <maherbenrhoumaaa@gmail.com>
 * @link     https://symfony.com/doc/current/controller.html
 */

namespace App\Controller\Account;

use App\Form\PasswordUserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

/**
 * PasswordController
 *
 * Handles password modification for users in their account section.
 *
 * @category Controllers
 * @package  App\Controller\Account
 */
class PasswordController extends AbstractController
{
    /**
     * Constructor
     *
     * @param EntityManagerInterface $entityManager The entity manager for database operations.
     */
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {
    }

    /**
     * Displays and processes the password modification form.
     *
     * @param Request                     $request        The HTTP request containing form data.
     * @param UserPasswordHasherInterface $passwordHasher The password hasher service for encrypting the password.
     *
     * @return Response Renders the password modification form or displays a success message.
     */
    #[Route('/account/modify-password', name: 'app_account_modify_pwd')]
    public function index(
        Request $request,
        UserPasswordHasherInterface $passwordHasher
    ): Response {
        $user = $this->getUser();

        $form = $this->createForm(
            PasswordUserType::class,
            $user,
            [
                'passwordHasher' => $passwordHasher
            ]
        );

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->addFlash(
                'success',
                'Your password has been updated'
            );

            $this->entityManager->flush();
        }

        return $this->render(
            'account/password/index.html.twig',
            [
                'modifyPwd' => $form->createView(),
            ]
        );
    }
}
