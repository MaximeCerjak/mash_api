<?php 

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

// class CategoryFixtures extends Fixture
// {
//     public function load(ObjectManager $manager)
//     {
//         $category = new Category();
//         $category->setName('Category 1');
//         $manager->persist($category);
//         $manager->flush();
//     }
// }