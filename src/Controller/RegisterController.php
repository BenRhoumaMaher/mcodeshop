<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterUserFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RegisterController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {
    }
    #[Route('/register', name: 'app_register')]
    public function index(Request $request): Response
    {
        $user = new User();
        $form = $this->createForm(RegisterUserFormType::class, $user);
        $form->handleRequest(
            $request
        );
        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($user);
            $this->entityManager->flush();
            $this->addFlash(
                'success',
                'your account has been created'
            );
            return $this->redirectToRoute('app_login');
        }
        return $this->render(
            'register/index.html.twig',
            [
            'registerform' => $form->createView()
            ]
        );
    }
}
