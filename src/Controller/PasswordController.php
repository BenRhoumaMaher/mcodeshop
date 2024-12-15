<?php

/**
 * PasswordController
 *
 * This file defines the PasswordController, responsible for handling password reset functionality.
 * It includes generating reset tokens, sending reset emails, and updating passwords.
 *
 * @category Controllers
 * @package  App\Controller\Password
 * @author   Maher Ben Rhouma <maherbenrhoumaaa@gmail.com>
 * @license  No license (Personal project)
 * @link     https://symfony.com/doc/current/security.html
 */

namespace App\Controller;

use DateTime;
use Src\Classe\Mail;
use App\Repository\UserRepository;
use App\Form\ResetPasswordFormType;
use App\Form\ForgetPasswordFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * PasswordController
 *
 * Manages password recovery processes,
 * including form handling and email notifications.
 *
 * @category Controllers
 * @package  App\Controller\Password
 * @link     https://symfony.com/doc/current/security.html
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
     * Displays and processes the password reset request form.
     *
     * @param Request        $request        The current HTTP request containing form data.
     * @param UserRepository $userRepository The repository for fetching user data.
     *
     * @return Response Renders the form or sends a reset email if the form is valid.
     */
    #[Route('/passwordforgot', name: 'app_password')]
    public function index(Request $request, UserRepository $userRepository): Response
    {
        $form = $this->createForm(ForgetPasswordFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $email = $form->get('email')->getData();
            $user = $userRepository->findOneByEmail($email);

            $this->addFlash(
                'success',
                'If your email is real, you will receive an email to reset your password.'
            );

            if ($user) {
                $token = bin2hex(random_bytes(15));
                $user->setToken($token);
                $date = new DateTime();
                $date->modify('+10 minutes');
                $user->setTokenExpiredAt($date);
                $this->entityManager->flush();

                $mail = new Mail();
                $vars = [
                    'link' => $this->generateUrl(
                        'app_password_update',
                        [
                            'token' => $token,
                        ],
                        UrlGeneratorInterface::ABSOLUTE_URL
                    ),
                ];
                $mail->send(
                    $user->getEmail(),
                    $user->getFirstName() . ' ' . $user->getLastName(),
                    'Forget Password',
                    "ForgotPassword.html",
                    $vars
                );
            }
        }

        return $this->render(
            'password/index.html.twig',
            [
                'forgotPasswordform' => $form->createView(),
            ]
        );
    }

    /**
     * Displays and processes the password reset form.
     *
     * @param Request        $request        The current HTTP request containing form data.
     * @param UserRepository $userRepository The repository for fetching user data.
     * @param string         $token          The reset token to validate the request.
     *
     * @return Response Renders the reset form or redirects if the token is invalid or expired.
     */
    #[Route('/passwordforgot/reset/{token}', name: 'app_password_update')]
    public function update(Request $request, UserRepository $userRepository, string $token): Response
    {
        if (!$token) {
            return $this->redirectToRoute('app_password');
        }

        $user = $userRepository->findOneByToken($token);
        $now = new DateTime();

        if (!$user || $now > $user->getTokenExpiredAt()) {
            return $this->redirectToRoute('app_password');
        }

        $form = $this->createForm(ResetPasswordFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setToken(null);
            $user->setTokenExpiredAt(null);
            $this->entityManager->flush();

            $this->addFlash('success', 'Your password has been updated.');

            return $this->redirectToRoute('app_login');
        }

        return $this->render(
            'password/reset.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }
}
