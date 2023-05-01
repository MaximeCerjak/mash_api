<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\CategoryArticleRepository;

class CategoryArticleController extends AbstractController
{
    public function __construct(
        private CategoryArticleRepository $categoryArticleRepository
    ) {
    }

    #[Route('/category/article', name: 'app_category_article')]
    public function index(): JsonResponse
    {
        $categories = $this->categoryArticleRepository->findAll();
        $data = [];

        foreach ($categories as $category) {
            $data[] = [
                'id' => $category->getId(),
                'name' => $category->getCatName(),
                'description' => $category->getCatDescription()
            ];
        }

        return $this->json($data);
    }
}
