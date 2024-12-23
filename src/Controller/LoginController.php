<?php

/**
 * LoginController
 *
 * This file defines the LoginController, responsible for handling user authentication.
 * It includes user login, logout, and error handling during authentication.
 *
 * @category Controllers
 * @package  App\Controller\Auth
 * @author   Maher Ben Rhouma <maherbenrhoumaaa@gmail.com>
 * @license  No license (Personal project)
 * @link     https://symfony.com/doc/current/security.html
 */

namespace App\Controller;

use App\Logger\AuthLogger;
use LogicException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * LoginController
 *
 * Manages user login and logout functionality, providing the login form and handling authentication errors.
 *
 * @category Controllers
 * @package  App\Controller\Auth
 * @author   Maher Ben Rhouma <maherbenrhoumaaa@gmail.com>
 * @license  No license (Personal project)
 * @link     https://symfony.com/doc/current/security.html
 */
class LoginController extends AbstractController
{
    /**
     * Constructor
     *
     * Initializes the controller with required dependencies.
     *
     * @param AuthenticationUtils $authenticationUtils Provides utilities for user authentication.
     * @param AuthLogger          $authLogger          Logs authentication events.
     */
    public function __construct(
        private AuthenticationUtils $authenticationUtils,
        private AuthLogger $authLogger
    ) {
    }

    /**
     * Displays the login page and handles authentication errors.
     *
     * @return Response Renders the login form with the last username and any authentication errors.
     */
    #[Route('/login', name: 'app_login')]
    public function index(): Response
    {
        $error = $this->authenticationUtils->getLastAuthenticationError();
        $lastUsername = $this->authenticationUtils->getLastUsername();

        if ($error) {
            $this->authLogger->logLoginFailure($lastUsername);
        } else {
            $this->authLogger->logLoginSuccess($lastUsername);
        }

        return $this->render(
            'login/index.html.twig',
            [
                'last_username' => $lastUsername,
                'error' => $error,
            ]
        );
    }

    /**
     * Handles user logout.
     *
     * This method is intercepted by Symfony's security system to handle logout functionality.
     *
     * @throws LogicException Always throws an exception since this method should not be called directly.
     */
    #[Route('/logout', name: 'app_logout')]
    public function logout(): never
    {
        throw new \LogicException(
            'This method can be blank - it will be intercepted by the logout key on your firewall.'
        );
    }
}
