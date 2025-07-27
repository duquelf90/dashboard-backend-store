<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $categories = [
            'Electrónica',
            'Ropa y Accesorios',
            'Hogar y Jardín',
            'Salud y Belleza',
            'Deportes y Actividades al Aire Libre',
            'Juguetes y Juegos',
            'Alimentos y Bebidas',
            'Automóvil y Motocicletas',
            'Libros y Revistas',
            'Tecnología y Gadgets',
        ];

        foreach ($categories as $categoryName) {
            $category = new Category();
            $category->setName($categoryName);
            $manager->persist($category);
        }

        $manager->flush();
    }
}
