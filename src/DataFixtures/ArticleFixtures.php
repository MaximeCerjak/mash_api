<?php

// namespace App\DataFixtures;

// use Faker\Factory;
// use App\Entity\User;
// use App\Entity\Article;
// use App\Entity\CategoryArticle;
// use Doctrine\Persistence\ObjectManager;
// use Doctrine\Bundle\FixturesBundle\Fixture;
// use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

// class ArticleFixtures extends Fixture
// {
//     private $passwordHasher;

//     public function __construct(UserPasswordHasherInterface $passwordHasher)
//     {
//         $this->passwordHasher = $passwordHasher;
//     }

//     public function load(ObjectManager $manager): void
//     {
//         $faker = Factory::create('fr_FR');

//         $superAdmin = new User();
//         $superAdmin->setUserEmail('admin@gmail.com');
//         $superAdmin->setUserStatus(0);
//         $superAdmin->setUserPassword($this->encoder->encodePassword($superAdmin, 'admin'));
//         $manager->persist($superAdmin);

//         $admin = new User();
//         $admin->setUserEmail('admin@gmail.com');
//         $admin->setUserStatus(1);
//         $admin->setUserPassword($this->encoder->encodePassword($admin, 'admin'));
//         $manager->persist($admin);

//         for( $i = 0; $i < 10; $i++) {
//             $user = new User();
//             $user->setUserEmail($faker->email);
//             $user->setUserStatus(2);
//             $user->setUserPassword($this->encoder->encodePassword($user, 'user'));
//             $manager->persist($user);
//         }

//         for( $i = 0; $i < 10; $i++) {
//             $category = new CategoryArticle();
//             $category->setCatName($faker->word);
//             $category->setCatDescription($faker->text);
//             $manager->persist($category);
//         }

//         for( $i = 0; $i < 10; $i++) {
//             $article = new Article();
//             $article->setArticleTitle($faker->sentence);
//             $article->setArticleContent($faker->text);
//             $article->setArticleDate($faker->dateTime);
//             $article->setArticleImage($faker->imageUrl(640, 480, 'animals', true, 'Faker'));
//             $article->setArticleCategory($faker->randomElement($category));
//             $article->setArticleUser($faker->randomElement($user));
//             $manager->persist($article);
//         }

//         $manager->flush();
//     }
// }
