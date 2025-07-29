<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
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
            ->add('sector', TextType::class, [
                'label' => 'sectores',
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
                    'class' => ''
                ],
                'expanded' => true, // Para usar botones de opción
                'multiple' => false, // Permitir múltiples roles
                'label' => 'Roles',
            ])
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,                
                'mapped' => false,
                'first_options' => [
                    'label'=> false,
                    'attr' => [
                        'class' => 'w-full block text-sm my-2 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input'
                    ],
                    'constraints' => [
                        new NotBlank(),
                        new Length(['min' => 6]),
                    ],
                ],
                'second_options' => [
                    'label' => 'Repeat New Password',
                    'attr' => [
                        'class' => 'w-full block text-sm my-2 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input'
                    ],
                    'constraints' => [
                        new NotBlank(),
                    ],
                ],
                'invalid_message' => 'The password fields must match.',
            ])
            ->add('aboutus');
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
