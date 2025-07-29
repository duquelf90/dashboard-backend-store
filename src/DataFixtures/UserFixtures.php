<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }
    public function load(ObjectManager $manager): void
    {
        // Crear admin
        $admin = new User();
        $admin->setUsername('admin');
        $admin->setEmail('admin@example.com');
        $admin->setRoles('ROLE_ADMIN');
        $admin->setPassword($this->passwordHasher->hashPassword($admin, '12345678')); // Cambia la contraseña según sea necesario
        $manager->persist($admin);

        // Crear negocio 1
        $negocio1 = new User();
        $negocio1->setUsername('negocio1');
        $negocio1->setEmail('negocio1@example.com');
        $negocio1->setRoles('ROLE_USER');
        $negocio1->setPassword($this->passwordHasher->hashPassword($negocio1, '111'));
        $manager->persist($negocio1);

        // Crear negocio 2
        $negocio2 = new User();
        $negocio2->setUsername('negocio2');
        $negocio2->setEmail('negocio2@example.com');
        $negocio2->setRoles('ROLE_USER');
        $negocio2->setPassword($this->passwordHasher->hashPassword($negocio2, '222'));
        $manager->persist($negocio2);

        // Guardar todos los usuarios
        $manager->flush();
    }
}
