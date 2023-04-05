<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index()
    {
        return new JsonResponse(['message' => 'Welcome to my API']);
    }

    /**
     * @Route("/error", name="error")
     */
    public function error()
    {
        return new JsonResponse(['message' => 'An error occurred'], 500);
    }
}
