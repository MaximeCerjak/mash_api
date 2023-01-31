<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Picture;
use Faker\Factory;

class PictureFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        for( $i = 0; $i < 10; $i++) {
            $picture = new Picture();
            $picture->setPicUrl($faker->imageUrl());
            $picture->setPicLegend($faker->sentence());
            $picture->setPicFormat($faker->fileExtension());
            $picture->setPicTitle($faker->sentence());
            $manager->persist($picture);
        }

        $manager->flush();
    }
}