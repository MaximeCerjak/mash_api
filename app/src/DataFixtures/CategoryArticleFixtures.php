<?php

namespace App\DataFixtures;

use App\Entity\CategoryArticle;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategoryArticleFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $categoriesData = [
            'Veille' => 'Des articles sur les dernières tendances, les nouveautés et les meilleures pratiques dans le domaine de la création graphique.',
            'Tutoriel' => 'Des guides pas à pas, des astuces et des conseils pour maîtriser les outils et techniques de création graphique.',
            'Blog' => 'Des articles de fond, des réflexions et des opinions sur le monde de la création graphique et des arts visuels.',
            'Actualités' => 'Des articles sur les actualités et les événements importants dans le domaine de la création graphique et des arts visuels.',
            'Ressources' => 'Une sélection de ressources graphiques gratuites ou premium, telles que des polices de caractères, des modèles, des icônes et des illustrations.',
            'Inspirations' => 'Des articles présentant des projets créatifs et innovants pour inspirer et motiver les créateurs.',
            'Interviews' => 'Des entretiens avec des créateurs de talent, des artistes et des professionnels du secteur pour partager leurs expériences et leurs conseils.',
            'Technologies' => 'Des articles sur les dernières technologies, les outils et les logiciels utilisés dans le domaine de la création graphique et des arts visuels.',
            'Portfolios' => 'Des présentations de portfolios d\'artistes et de créateurs pour montrer leur travail et leur style.',
            'Critiques' => 'Des analyses et des évaluations de produits, de services et de ressources liés à la création graphique et aux arts visuels.',
        ];

        foreach ($categoriesData as $catName => $catDescription) {
            $categoryArticle = new CategoryArticle();
            $categoryArticle->setCatName($catName);
            $categoryArticle->setCatDescription($catDescription);
            $manager->persist($categoryArticle);
            $this->addReference('category_' . $catName, $categoryArticle);
        }

        $manager->flush();
    }
}
