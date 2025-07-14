<?php

namespace App\Controller;

use App\Service\Salesforce;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

final class SalesforceController extends AbstractController
{

    #[Route('/api/salesforce/connect', name: 'connect', methods: ['POST'])]
    public function connect(Request $request, Salesforce $salesforce, SerializerInterface $serializer): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $salesforce->authenticate();
        $response = $salesforce->createAccount($data);
        $salesforce->AddContact($data, $response['id']);
        $jsonResponse = $serializer->serialize($response, 'json');
        return new JsonResponse($jsonResponse);
    }

    #[Route('salesforce/account', name: 'account', methods: ['POST'])]
    public function createAccount(Request $request, Salesforce $salesforce, SerializerInterface $serializer): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $salesforce->authenticate();
        $response = $salesforce->createAccount($data);
        $json = $serializer->serialize($response, 'json');
        return new JsonResponse($json, 200, [], true);
    }

    #[Route('salesforce/contact', name: 'contact', methods: ['POST'])]
    public function addContact(Request $request, Salesforce $salesforce, SerializerInterface $serializer): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $salesforce->authenticate();
        $response = $salesforce->addContact($data, $data['account_id']);
        $json = $serializer->serialize($response, 'json');
        return new JsonResponse($json, 200, [], true);
    }
}
