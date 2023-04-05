<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Picture;
use Faker\Factory;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class PictureFixtures extends Fixture implements FixtureGroupInterface, DependentFixtureInterface
{
    public static function getGroups(): array
    {
        return ['user', 'category', 'picture'];
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        for ($i = 0; $i < 10; $i++) {
            $picture = new Picture();
            $picture->setPicUrl($faker->imageUrl());
            $picture->setPicLegend($faker->sentence());
            $picture->setPicFormat($faker->fileExtension());
            $picture->setPicTitle($faker->sentence());
            $picture->setUser($this->getReference('user_' . rand(0, 9)));
            $picture->setCategory($this->getReference('category_Graphisme'));
            $manager->persist($picture);
        }

        $manager->flush();
    }
    /**
     * This method must return an array of fixtures classes
     * on which the implementing class depends on
     * @return array<string>
     */
    public function getDependencies()
    {
        return [
            UserFixtures::class,
            CategoryFixtures::class,
        ];
    }
}
