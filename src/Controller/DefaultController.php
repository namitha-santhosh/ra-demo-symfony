<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController
{
    /**
     * @Route("/", name="home", methods={"GET"})
     */
    public function home(): JsonResponse
    {
        return new JsonResponse(['message' => 'Welcome to the API']);
    }
}