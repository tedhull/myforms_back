<?php

namespace App\Controller;

use App\Entity\Field;
use App\Entity\Template;
use App\Repository\TemplateRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

final class TemplatesController extends AbstractController
{

    #[Route('api/templates', name: 'app_templates')]
    public function index(): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/TemplatesController.php',
        ]);
    }

    #[Route('api/templates/new', name: 'create', methods: ['POST'])]
    public function createTemplate(Request $request, EntityManagerInterface $emi): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $template = new Template();
        $template->setCreatedAt(date_create_immutable());
        $template->setUpdatedAt(date_create_immutable());
        $template->setCreator($this->getUser());
        $template->setTopic($data['topic']);
        $template->setTags($data['tags']);
        $template->setTitle($data['title']);
        $template->setDescription($data['description']);


        $fields = $data['fields'];
        $index = 0;
        foreach ($fields as $field) {
            $fieldEntity = new Field();

            $fieldEntity->setTitle($field['title']);
            $fieldEntity->setDescription($field['description']);
            $fieldEntity->setType($field['type']);
            $fieldEntity->setIsRequired($field['required']);
            $fieldEntity->setPosition($index);
            $fieldEntity->setOptions($field['options']);
            $fieldEntity->setTemplate($template);
            $emi->persist($fieldEntity);
            $index++;
        }
        $emi->persist($template);
        $emi->flush();
        return new JsonResponse($data);
    }

    #[Route('api/templates/{id}', name: 'get', methods: ['GET'])]
    public function getTemplate($id, TemplateRepository $templateRepository, SerializerInterface $serializer): JsonResponse
    {
        $template = $templateRepository->find($id);
        if (!$template) {
            return $this->json(['error' => 'Template not found'], 404);
        }
        $json = $serializer->serialize($template, 'json', ['groups' => 'template:read']);
        return new JsonResponse($json, 200, [], true);
    }
}
