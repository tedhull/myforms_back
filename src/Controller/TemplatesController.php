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
    #[Route('/api/templates/latest', name: 'app_api_templates_latest', methods: ['GET'])]
    public function index(TemplateRepository $templateRepository, SerializerInterface $serializer): JsonResponse
    {
        $templates = $templateRepository->findBy([], ['createdAt' => 'DESC'], 5);
        $json = $serializer->serialize($templates, 'json', ['groups' => ['template:list']]);
        return new JsonResponse(['message' => "templates fetched successfully", 'templates' => $json]);
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
            $fieldEntity->setPosition($index);
            $this->setField($fieldEntity, $field, $template, $emi);
            $index++;
        }
        $emi->persist($template);
        $emi->flush();
        return new JsonResponse(['message' => 'Template created successfully', 'id' => $template->getId()], 201);
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

    #[Route('/api/templates/delete', name: 'delete', methods: ['DELETE'])]
    public function deleteTemplate(EntityManagerInterface $emi, SerializerInterface $serializer, Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $ids = $data['ids'];
        foreach ($ids as $id) {
            $template = $emi->getRepository(Template::class)->find($id);
            if ($template) {
                $emi->remove($template);
            }
        }
        $emi->flush();
        $json = $serializer->serialize($ids, 'json');
        return new JsonResponse(['message' => "deleted successfully", 'ids' => $json], 204);
    }

    #[Route('api/templates/update/{id}', name: 'update', methods: ['PUT'])]
    public function updateTemplate($id, Request $request, SerializerInterface $serializer, EntityManagerInterface $emi): JsonResponse
    {
        $template = $emi->getRepository(Template::class)->find($id);
        if (!$template) {
            return $this->json(['error' => 'Template not found'], 404);
        }

        $data = json_decode($request->getContent(), true);

        $template->setTitle($data['title']);
        $template->setDescription($data['description']);
        $template->setTopic($data['topic']);
        $template->setTags($data['tags']);
        $template->setUpdatedAt(date_create_immutable());
        foreach ($data['deletedBlockIds'] as $deletedId) {
            $field = $emi->getRepository(Field::class)->find($deletedId);
            if ($field) {
                $emi->remove($field);
            }
        }

        foreach ($data['updatedBlocks'] as $field) {
            $fieldEntity = $emi->getRepository(Field::class)->find($field['id']);

            if (!$fieldEntity) $fieldEntity = new Field();
            $fieldEntity->setPosition($field['position']);
            $this->setField($fieldEntity, $field, $template, $emi);
        }
        $emi->persist($template);
        $emi->flush();
        $json = $serializer->serialize($data, 'json', ['groups' => 'template:read']);
        return new JsonResponse($json, 200, [], true);
    }

    function setField(Field $entity, array $data, Template $template, EntityManagerInterface $emi): void
    {
        if ($data['type'] === 'question') {
            $entity->setTitle($data['title']);
            $entity->setDescription($data['description']);
            $entity->setIsRequired($data['isRequired']);
            $entity->setQuestionType($data['questionType']);
            $entity->setOptions($data['options']);
        } else {
            $entity->setDescription('');
            $entity->setKey($data['key']);
            $entity->setCaption($data['caption']);
            $entity->setPreview($data['preview']);
        }
        $entity->setType($data['type']);
        $entity->setTemplate($template);
        $emi->persist($entity);
    }
}
