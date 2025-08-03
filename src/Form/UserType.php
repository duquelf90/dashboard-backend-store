<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\EqualTo;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username', TextType::class, [
                'label' => 'Nombre de Usuario',
                'attr' => [
                    'class' => 'w-full block text-sm my-2 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input'
                ]
            ])
            ->add('email', EmailType::class, [
                'label' => 'Correo Electrónico',
                'attr' => [
                    'class' => 'w-full block text-sm my-2 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input'
                ]
            ])
            ->add('address', TextType::class, [
                'label' => 'Direccion',
                'attr' => [
                    'class' => 'w-full block text-sm my-2 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input'
                ]
            ])
            ->add('telephone', TextType::class, [
                'label' => 'telefono',
                'attr' => [
                    'class' => 'w-full block text-sm my-2 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input'
                ]
            ])

            ->add('roles', ChoiceType::class, [
                'choices' => [
                    'Admin' => 'ROLE_ADMIN',
                    'Negocio' => 'ROLE_USER',
                ],
                'attr' => [
                    'class' => 'flex flex-row my-2 space-x-4 text-gray-600 dark:text-gray-300'
                ],
                'multiple' => false,
                'expanded' => true,
            ])
            ->add('password', PasswordType::class, [
                'label' => 'New Password',
                'attr' => [
                    'class' => 'w-full block text-sm my-2 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input'
                ],
                'constraints' => [
                    new NotBlank(),
                    new Length(['min' => 2]),
                ],
            ])
            ->add('confirmPassword', PasswordType::class, [
                'mapped' => false,
                'label' => 'Repeat New Password',
                'attr' => [
                    'class' => 'w-full block text-sm my-2 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input'
                ],
                'constraints' => [
                    new NotBlank(),
                ],
            ])
            ->add('aboutus', TextareaType::class, [
                'required' => true,
                'attr' => [
                    'rows' => 5,
                    'placeholder' => 'Descripcion del negocio',
                    'class' => 'w-full block text-sm my-2 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
