<?php

/**
 * RegisterController
 *
 * This file defines the RegisterController, responsible for handling user registration.
 * It includes user creation, form handling, database persistence, and sending a welcome email.
 *
 * @category Controllers
 * @package  App\Controller\Register
 * @author   Maher Ben Rhouma <maherbenrhoumaaa@gmail.com>
 * @license  No license (Personal project)
 * @link     https://symfony.com/doc/current/controller.html
 */

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterUserFormType;
use Doctrine\ORM\EntityManagerInterface;
use Src\Classe\Mail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * RegisterController
 *
 * Manages user registration, including handling registration forms, saving users to the database, and sending welcome emails.
 *
 * @category Controllers
 * @package  App\Controller\Register
 * @author   Maher Ben Rhouma <maherbenrhoumaaa@gmail.com>
 * @license  No license (Personal project)
 * @link     https://symfony.com/doc/current/controller.html
 */
class RegisterController extends AbstractController
{
    /**
     * Constructor
     *
     * Initializes the controller with required dependencies.
     *
     * @param EntityManagerInterface $entityManager The entity manager to handle database operations.
     */
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {
    }

    /**
     * Displays and processes the user registration form.
     *
     * Handles form submission, persists valid user data to the database, and sends a welcome email.
     *
     * @param Request $request The current HTTP request containing form data.
     *
     * @return Response Renders the registration form or redirects to the login page upon successful registration.
     */
    #[Route('/register', name: 'app_register')]
    public function index(Request $request): Response
    {
        $user = new User();
        $form = $this->createForm(RegisterUserFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($user);
            $this->entityManager->flush();

            $this->addFlash('success', 'Your account has been created');

            $mail = new Mail();
            $vars = [
                'firstname' => $user->getFirstname(),
            ];
            $mail->send(
                $user->getEmail(),
                $user->getFirstname() . ' ' . $user->getLastName(),
                'Welcome',
                "Welcome.html",
                $vars
            );

            return $this->redirectToRoute('app_login');
        }

        return $this->render(
            'register/index.html.twig',
            [
                'registerform' => $form->createView(),
            ]
        );
    }
}
