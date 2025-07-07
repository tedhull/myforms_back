<?php

namespace App\Controller;

use App\Entity\Field;
use App\Entity\Form;
use App\Entity\FormResponse;
use App\Entity\Template;
use App\Repository\FieldRepository;
use App\Repository\FormRepository;
use App\Repository\FormResponseRepository;
use App\Repository\TemplateRepository;
use App\Repository\UserRepository;
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

    #[Route('/api/form/submit', name: 'set_form', methods: ['POST'])]
    public function submit(Request $request, TemplateRepository $templateRepository, UserRepository $userRepository, FieldRepository $fieldRepository, FormResponseRepository $responseRepository, FormRepository $formRepository, EntityManagerInterface $emi, SerializerInterface $serializer): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $userId = $data['userId'];
        $user = $userRepository->find($userId ?: $this->getUser());
        $form = $formRepository->findOneBy(['respondent' => $user ?: $this->getUser()]);
        if (!$form) $form = new Form();
        $form->setRespondent($userId ? $userRepository->find($userId) : $this->getUser());
        $form->setTemplate($templateRepository->find($data['templateId']));
        $formData = $data['formData'];
        $emi->persist($form);
        foreach ($formData as $response) {
            $field = $fieldRepository->find($response['id']);
            $this->deleteIfExists($field, $responseRepository, $form);
            $responseEntity = new FormResponse();
            $responseEntity->setValue([$response['value']]);
            $responseEntity->setQuestion($field);
            $responseEntity->setForm($form);
            $emi->persist($responseEntity);
        }
        $emi->flush();
        $formData = $serializer->serialize($data, 'json');
        return new JsonResponse(['message' => 'Form submitted successfully', 'data' => $formData], 201);
    }

    #[Route('/api/form/{id}/{userId}', name: 'get_form', methods: ['GET'])]
    public function getForm($id, $userId, TemplateRepository $templateRepository, Request $request, UserRepository $userRepository, FormRepository $formRepository, FormResponseRepository $responseRepository, SerializerInterface $serializer): JsonResponse
    {
        $form = $formRepository->findOneBy(['template' => $templateRepository->find($id), 'respondent' => $userRepository->find($userId)]);
        $responses = $form->getFields();
        $json = $serializer->serialize($responses, 'json', ['groups' => ['form:read']]);
        return new JsonResponse(['message' => 'Form fetched successfully', 'data' => ['fields' => $json, 'id' => $this->getUser()->getId()], 200]);
    }

    #[Route('/api/form/{id}', name: 'list_forms', methods: ['GET'])]
    public function listForms($id, Request $request, TemplateRepository $templateRepository, SerializerInterface $serializer): JsonResponse
    {
        $template = $templateRepository->find($id);
        $forms = $template->getForms();
        $json = $serializer->serialize($forms, 'json', ['groups' => ['form:read']]);
        return new JsonResponse(['message' => 'Form listed successfully', 'data' => $json], 200);
    }

    private function deleteIfExists(Field $field, $responseRepository, Form $form): void
    {
        $response = $responseRepository->findOneBy(['question' => $field, 'form' => $form]);
        if ($response) {
            $field->removeFormResponse($response);
        }
    }
}
