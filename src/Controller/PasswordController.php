<?php

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

class PasswordController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {
    }
    #[Route('/passwordforgot', name: 'app_password')]
    public function index(Request $request, UserRepository $userRepository): Response
    {
        $form = $this->createForm(
            ForgetPasswordFormType::class
        );
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $email = $form->get('email')->getData();
            $user = $userRepository->findOneByEmail($email);
            $this->addFlash(
                'success',
                'if your email is real, 
                youll receive an email to reset your password'
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
                        'token' => $token
                        ],
                        UrlGeneratorInterface::ABSOLUTE_URL
                    ),
                ];
                $mail->send(
                    $user->getEmail(),
                    $user->getFirstName(). ' ' .$user->getLastName(),
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

    #[Route(
        '/passwordforgot/reset/{token}',
        name: 'app_password_update'
    )]
    public function update(
        Request $request,
        UserRepository $userRepository,
        $token
    ): Response {
        if (!$token) {
            return $this->redirectToRoute('app_password');
        }
        $user = $userRepository->findOneByToken($token);
        $now = new DateTime();
        if (!$user || $now > $user->getTokenExpiredAt()) {
            return $this->redirectToRoute('app_password');
        }
        $form = $this->createForm(
            ResetPasswordFormType::class
        );
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user->setToken(null);
            $user->setTokenExpiredAt(null);
            $this->entityManager->flush();
            $this->addFlash(
                'success',
                'your password has been updated'
            );
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
