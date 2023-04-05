<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategoryFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $categoriesData = [
            'Vidéos' => 'Des ressources vidéo pour les créateurs, y compris des séquences libres de droits, des modèles d\'animation et des éléments de transition.',
            'Audio' => 'Une sélection de fichiers audio, tels que des musiques d\'ambiance, des effets sonores et des voix off pour donner vie à vos créations.',
            'Typographie' => 'Une variété de polices de caractères et de styles typographiques pour ajouter du caractère et de la personnalité à vos projets de design.',
            'Modélisation 3D' => 'Des modèles 3D pré-conçus, des textures et des scènes pour faciliter la création de projets d\'animation et de rendu en trois dimensions.',
            'GIF' => 'Une collection de GIF animés pour ajouter du dynamisme et de l\'humour à vos créations numériques.',
            'Photographie' => 'Des images haute résolution et libres de droits pour embellir vos projets et mettre en valeur vos idées.',
            'Graphisme' => 'Des éléments graphiques tels que des icônes, des vecteurs et des illustrations pour donner une touche artistique à vos créations.'
        ];

        foreach ($categoriesData as $catName => $catDescription) {
            $category = new Category();
            $category->setCatName($catName);
            $category->setCatDescription($catDescription);
            $manager->persist($category);
            $this->addReference('category_' . $catName, $category);
        }

        $manager->flush();
    }
}
