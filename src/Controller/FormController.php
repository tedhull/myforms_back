<?php

namespace App\Controller;

use App\Repository\TemplateRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

final class FormController extends AbstractController
{
    #[Route('/form', name: 'app_form')]
    public function index(): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/FormController.php',
        ]);
    }

    #[Route('/api/form/submit', name: 'app_form_get', methods: ['POST'])]
    public function submit(Request $request, TemplateRepository $templateRepository, SerializerInterface $serializer): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $formData = $data['formData'];
        $formData = $serializer->serialize($formData, 'json');
        return new JsonResponse(['message' => 'Form submitted successfully', 'formData' => $formData], 201);
    }
}
