<?php

namespace App\Form;

use App\Entity\Invoice;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InvoiceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('invoice', null, [
                'mapped' => false,
                'attr' => [
                    'placeholder' => 'ST-343434',
                    'class' => 'w-full block text-sm my-2 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input'
                ]
            ])
            ->add('customer', null, [
                'mapped' => false, // Campo no mapeado
                'attr' => [
                    'placeholder' => 'Nombre del cliente',
                    'class' => 'w-full block text-sm my-2 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input'
                ]
            ])
            ->add('email', EmailType::class, [
                'mapped' => false,
                'attr' => [
                    'placeholder' => 'customer@example.com',
                    'class' => 'w-full block text-sm my-2 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input'
                ]
            ])
            ->add('phone', TelType::class, [
                'mapped' => false, // Campo no mapeado
                'attr' => [
                    'placeholder' => 'Numero del cliente',
                    'class' => 'w-full block text-sm my-2 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input'
                ]
            ])
            ->add('address', null, [
                'mapped' => false, // Campo no mapeado
                'attr' => [
                    'placeholder' => 'Direccion del cliente',
                    'class' => 'w-full block text-sm my-2 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input'
                ]
            ])
            ->add('description', TextareaType::class, [
                'mapped' => false,
                'attr' => [
                    'rows' => 5,
                    'placeholder' => 'Informacion adicional (opcional)',
                    'class' => 'w-full block text-sm my-2 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // 'data_class' => Invoice::class,
        ]);
    }
}
