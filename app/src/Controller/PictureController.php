<?php

namespace App\Controller;

use App\Repository\PictureRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class PictureController extends AbstractController
{
    #[Route('/picture', name: 'app_picture')]
    public function index(PictureRepository $pictureRepository): JsonResponse
    {
        $pictures = $pictureRepository->findAll();
        $data = [];
        foreach ($pictures as $picture) {
            $data[] = [
                'id' => $picture->getId(),
                'title' => $picture->getPicTitle(),
                'url' => $picture->getPicUrl(),
                'legend' => $picture->getPicLegend(),
                'format' => $picture->getPicFormat(),
                'description' => $picture->getCreaDescription()
            ];
        }
        return $this->json($data);
    }
}
