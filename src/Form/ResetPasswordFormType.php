<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class ResetPasswordFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
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
                    'placeholder' => 'Enter your new password'
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
                'submit',
                SubmitType::class,
                [
                'label' => 'Reset password',
                'attr' => [
                'class' => 'btn btn-warning'
                ]
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
            'data_class' => User::class
            ]
        );
    }
}
