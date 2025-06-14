<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

final class OtherController extends AbstractController
{
    #[Route('/other', name: 'app_other')]
    public function index(): JsonResponse
    {
        return $this->json([
            'message' => 'Hi! this is response from controller 1',
            'path' => 'src/Controller/OtherController.php',
        ]);
    }
}
