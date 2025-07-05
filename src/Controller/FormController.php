<?php

namespace App\Controller;

use App\Entity\Field;
use App\Entity\FormResponse;
use App\Repository\FieldRepository;
use App\Repository\FormResponseRepository;
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
    public function submit(Request $request, FieldRepository $fieldRepository, FormResponseRepository $responseRepository, EntityManagerInterface $emi, SerializerInterface $serializer): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $formData = $data['formData'];
        foreach ($formData as $response) {
            $field = $fieldRepository->find($response['id']);
            $this->deleteIfExists($field, $responseRepository);
            $responseEntity = new FormResponse();
            $responseEntity->setQuestion($field);
            $responseEntity->setRespondent($this->getUser());
            $this->setResponse($response, $responseEntity);
            $emi->persist($responseEntity);
        }
        $emi->flush();
        $formData = $serializer->serialize($formData, 'json');
        return new JsonResponse(['message' => 'Form submitted successfully', 'data' => $formData], 201);
    }


    private function deleteIfExists(Field $field, $responseRepository): void
    {
        $response = $responseRepository->findOneBy(['question' => $field, 'respondent' => $this->getUser()]);
        if ($response) {
            $field->removeFormResponse($response);
        }
    }

    private function setResponse($jsonResponse, FormResponse $responseEntity): void
    {

        switch ($jsonResponse['type']) {
            case 'single-line':
            case 'paragraph':
                $responseEntity->setTextResponse($jsonResponse['value']);
                break;

            case 'number':
            case 'one-from-list':
                $responseEntity->setNumericResponse($jsonResponse['value']);
                break;

            case 'few-from-list':
            default:
                $responseEntity->setPickedOptions($jsonResponse['value']);
                break;
        }
    }
}
