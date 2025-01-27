<?php

namespace App\DataFixtures;

use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    private const LOREM = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas eu euismod ex. Integer bibendum libero quis ex tempor, a dapibus dolor laoreet. Nullam eu quam sit amet quam posuere eleifend. Etiam sit amet purus aliquam, interdum nibh at, fringilla nisl. Donec mattis dolor sit amet magna scelerisque pellentesque. Mauris ut volutpat neque, et facilisis odio. Nam in efficitur augue. Sed suscipit nisi ac sem viverra commodo. Nulla placerat libero sit amet mauris congue mollis. Integer sed arcu gravida, ultrices odio nec, commodo nunc.

Nulla eu elit lectus. Pellentesque in hendrerit nisl. Aliquam ultrices, sem in molestie placerat, magna purus imperdiet sapien, et efficitur nisi nisl non eros. Quisque varius aliquet nisi laoreet rhoncus. Morbi vitae turpis condimentum, mollis magna nec, lacinia leo. Sed et augue non nisi vulputate laoreet. Sed finibus vel nibh ac euismod. Nunc vestibulum vitae erat at ullamcorper. Phasellus cursus, lacus eget gravida blandit, libero sem euismod elit, vitae pretium lacus lacus ac turpis.
';

    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < 12; $i++) {
            $product = new Product();
            $product->setName('product ' . $i)
                ->setDescription(self::LOREM)
                ->setPrice(rand(1000, 10000) / 100);
            $manager->persist($product);
        }


        $manager->flush();
    }
}
