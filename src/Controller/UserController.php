<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

final class UserController extends AbstractController
{
    #[Route('/api/user/forms', name: 'user_view', methods: ['GET'])]
    function getForms(SerializerInterface $serializer)
    {
        $user = $this->getUser();
        $forms = $user->getForms();
        $json = $serializer->serialize($forms, 'json', ['groups' => ['form:list']]);
        return new JsonResponse($json);
    }

    #[Route('/api/user/templates')]
    public function getTemplates(SerializerInterface $serializer)
    {
        $user = $this->getUser();
        $templates = $user->getTemplates();
        $json = $serializer->serialize($templates, 'json', ['groups' => ['template:list']]);
        return new JsonResponse($json);
    }
}
