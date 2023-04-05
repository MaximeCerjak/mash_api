<?php

namespace App\DataFixtures;

use App\Entity\Article;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Faker\Factory;

class ArticleFixtures extends Fixture implements DependentFixtureInterface, FixtureGroupInterface
{
    public static function getGroups(): array
    {
        return ['user', 'category', 'article'];
    }

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');

        for ($i = 0; $i < 10; $i++) {
            $article = new Article();
            $article->setArticleTitle($faker->sentence());
            $article->setArticleContent($faker->paragraph());
            $article->setArticleDate($faker->dateTime());
            $article->setCategoryArticle($this->getReference('category_Blog'));
            $article->setUser($this->getReference('user_' . rand(0, 9)));
            $manager->persist($article);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class,
            CategoryArticleFixtures::class,
        ];
    }
}
