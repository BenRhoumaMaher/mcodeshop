<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Validator\Constraints\Length;

class RegisterUserFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'attr' => [
                    'placeholder' => 'Enter your email'
                ]
            ])
            ->add(
                'plainPassword',
                RepeatedType::class,
                [
                'type' => PasswordType::class,
                'constraints' => [
                    new Length(
                        [
                        'min' => 6,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        'max' => 30,
                        'maxMessage' => 'Your password should be at most {{ limit }} characters'
                        ]
                    )
                ],
                'first_options' => [
                    'label' => 'Password',
                    'attr' => [
                        'placeholder' => 'Enter your password'
                    ],
                    'hash_property_path' => 'password'
                ],
                'second_options' => [
                    'label' => 'Repeat password',
                    'attr' => [
                        'placeholder' => 'Repeat your password'
                    ]
                    ],
                    'mapped' => false,
                    'invalid_message' => 'The password fields must match.',
                ]
            )
            ->add(
                'firstname',
                TextType::class,
                [
                'label' => 'First name',
                'attr' => [
                    'placeholder' => 'Enter your first name'
                ]
                ]
            )
            ->add(
                'lastname',
                TextType::class,
                [
                'label' => 'Last name',
                'attr' => [
                    'placeholder' => 'Enter your last name'
                ]
                ]
            )
            ->add(
                'submit',
                SubmitType::class,
                [
                'label' => 'Register',
                'attr' => [
                    'class' => 'btn btn-success'
                ]
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
            'constraints' => [
                new UniqueEntity(
                    [
                    'entityClass' => User::class,
                    'fields' => ['email'],
                    'message' => 'There is already an account with this email']
                )
            ],
            'data_class' => User::class,
            ]
        );
    }
}
