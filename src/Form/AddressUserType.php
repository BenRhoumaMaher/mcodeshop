<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Address;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;

class AddressUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'firstname',
                TextType::class,
                [
                'label' => 'Your name',
                'attr' => [
                    'placeholder' => 'Your name...'
                ]
                ]
            )
            ->add(
                'lastname',
                TextType::class,
                [
                'label' => 'Your lastname',
                'attr' => [
                    'placeholder' => 'Your lastname...'
                ]
                ]
            )
            ->add(
                'address',
                TextType::class,
                [
                'label' => 'Your address',
                'attr' => [
                    'placeholder' => 'Your address...'
                ]
                ]
            )
            ->add(
                'postal',
                TextType::class,
                [
                'label' => 'Your code',
                'attr' => [
                    'placeholder' => 'Your code...'
                ]
                ]
            )
            ->add(
                'city',
                TextType::class,
                [
                'label' => 'Your city',
                'attr' => [
                    'placeholder' => 'Your city...'
                ]
                ]
            )
            ->add(
                'country',
                CountryType::class,
                [
                'label' => 'Your Country',
                'attr' => [
                    'placeholder' => 'Your country...'
                ]
                ]
            )
            ->add(
                'phone',
                TextType::class,
                [
                'label' => 'Your phone',
                'attr' => [
                    'placeholder' => 'Your phone...'
                ]
                ]
            )
            ->add(
                'submit',
                SubmitType::class,
                [
                    'label' => 'Save',
                    'attr' => [
                        'class' => 'btn btn-success'
                    ]
                    ]
            );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Address::class,
        ]);
    }
}
