<?php

/**
 * PasswordUserType
 *
 * This file defines the form type for password modification.
 * It includes validation for the current password and constraints
 * for the new password.
 *
 * @category Forms
 * @package  App\Form
 * @author maher ben rhouma <maherbenrhoumaaa@gmail.com>
 * @license  No license (Personal project)
Maher Ben Rhouma <maherbenrhoumaaa@gmail.com>
 * @link     https://symfony.com/doc/current/forms.html
 */

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

/**
 * PasswordUserType
 *
 * Defines the form for password modification.
 *
 * @category Forms
 * @package  App\Form
 */
class PasswordUserType extends AbstractType
{
    /**
     * Builds the password modification form.
     *
     * @param FormBuilderInterface $builder The form builder instance.
     * @param array                $options Options for form configuration.
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'actualPassword',
                PasswordType::class,
                [
                    'label' => 'Current Password',
                    'attr' => [
                        'placeholder' => 'Enter your current password',
                    ],
                    'mapped' => false,
                ]
            )
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
                                'maxMessage' => 'Your password should be at most {{ limit }} characters',
                            ]
                        )
                    ],
                    'first_options' => [
                        'label' => 'Password',
                        'attr' => [
                            'placeholder' => 'Enter your new password',
                        ],
                        'hash_property_path' => 'password',
                    ],
                    'second_options' => [
                        'label' => 'Repeat password',
                        'attr' => [
                            'placeholder' => 'Repeat your password',
                        ],
                    ],
                    'mapped' => false,
                    'invalid_message' => 'The password fields must match.',
                ]
            )
            ->add(
                'submit',
                SubmitType::class,
                [
                    'label' => 'Modify',
                    'attr' => [
                        'class' => 'btn btn-success',
                    ],
                ]
            )
            ->addEventListener(
                FormEvents::SUBMIT,
                function (FormEvent $event) {
                    $form = $event->getForm();
                    $user = $form->getConfig()->getOptions()['data'];
                    $passwordHasher = $form->getConfig()
                        ->getOptions()['passwordHasher'];

                    $isValid = $passwordHasher->isPasswordValid(
                        $user,
                        $form->get('actualPassword')->getData()
                    );

                    if (!$isValid) {
                        $form->get(
                            'actualPassword'
                        )->addError(
                            new FormError(
                                'Your passwords are not matching'
                            )
                        );
                    }
                }
            );
    }

    /**
     * Configures form options, including the password hasher and
     * associated data class.
     *
     * @param OptionsResolver $resolver The options resolver instance.
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
                'data_class' => User::class,
                'passwordHasher' => null,
            ]
        );
    }
}
