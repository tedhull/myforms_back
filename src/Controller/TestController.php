<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

final class TestController extends AbstractController
{
    #[Route('/', name: 'app_test')]
    public function index(): JsonResponse
    {
        return $this->json([
            'message' => 'Hi! this is response from controller 2',
            'path' => 'src/Controller/TestController.php',
        ]);
    }

    #[Route('/db-test')]
    public function testDb(EntityManagerInterface $em): JsonResponse
    {
        try {
            $em->getConnection()->connect();
            return new JsonResponse(['status' => 'connected']);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()]);
        }
    }
}
