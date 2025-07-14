<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class Salesforce
{
    private HttpClientInterface $client;
    private $clientId;
    private $clientSecret;
    private $username;
    private $password;
    private $securityToken;
    private $accessToken;
    private $instanceUrl;

    public function __construct(HttpClientInterface $client, ParameterBagInterface $params)
    {
        $this->client = $client;
        $this->clientId = $_ENV['SALESFORCE_CLIENT_ID'];
        $this->clientSecret = $_ENV['SALESFORCE_CLIENT_SECRET'];
        $this->username = $_ENV['SALESFORCE_USERNAME'];
        $this->password = $_ENV['SALESFORCE_PASSWORD'];
        $this->securityToken = $_ENV['SALESFORCE_SECURITY_TOKEN'];
    }

    public function authenticate(): array
    {
        $response = $this->client->request('POST', 'https://login.salesforce.com/services/oauth2/token', [

            'body' =>
                [
                    'grant_type' => 'password',
                    'client_id' => $this->clientId,
                    'client_secret' => $this->clientSecret,
                    'username' => $this->username,
                    'password' => $this->password,
                ]
        ]);
        $data = json_decode($response->getContent(), true);
        $this->accessToken = $data['access_token'];
        $this->instanceUrl = $data['instance_url'];
        return $response->toArray();
    }

    public function createAccount(array $data): array
    {
        $companyResponse = $this->client->request('POST', $this->instanceUrl . '/services/data/v64.0/sobjects/Account', [
            'headers' => [
                'Authorization' => 'Bearer ' . $this->accessToken,
                'Content-Type' => 'application/json',
            ],
            'json' => [
                'Name' => $data['Company name'],
                'Phone' => $data['Company phone'] ?? null,
                'Website' => $data['Company website'] ?? null,
                'Industry' => $data['Industry'] ?? null,
                'Type' => $data['Type'] ?? null,
                'BillingStreet' => $data['Billing address street    '] ?? null,
                'BillingCity' => $data['Billing address city'] ?? null,
                'BillingState' => $data['Billing address state'] ?? null,
                'BillingPostalCode' => $data['Billing postal code'] ?? null,
                'BillingCountry' => $data['Billing country'] ?? null,
                'ShippingStreet' => $data['Shipping address street'] ?? null,
                'ShippingCity' => $data['Shipping address city'] ?? null,
                'ShippingState' => $data['Shipping address state'] ?? null,
                'ShippingPostalCode' => $data['Shipping postal code'] ?? null,
                'ShippingCountry' => $data['Shipping country'] ?? null,
                'NumberOfEmployees' => $data['Number of employees'] ?? null,
                'AnnualRevenue' => $data['Estimated annual revenue'] ?? null,
                'Description' => $data['Description'] ?? null,

            ]
        ]);
        return $companyResponse->toArray();
    }

    public function AddContact(array $data, string $accountId): array
    {
        $response = $this->client->request('POST', $this->instanceUrl . '/services/data/v64.0/sobjects/Contact', [
            'headers' => [
                'Authorization' => 'Bearer ' . $this->accessToken,
                'Content-Type' => 'application/json',
            ],
            'json' => [
                'FirstName' => $data['Contact first name'] ?? null,
                'LastName' => $data['Contact last name'],
                'Email' => $data['Contact email'] ?? null,
                'Phone' => $data['Contact phone'] ?? null,
                'Title' => $data['Job title'] ?? null,
                'Department' => $data['Department name'] ?? null,
                'MailingStreet' => $data['Mailing street address'] ?? null,
                'MailingCity' => $data['Mailing city'] ?? null,
                'MailingState' => $data['Mailing state'] ?? null,
                'MailingPostalCode' => $data['Mailing postal code'] ?? null,
                'MailingCountry' => $data['Mailing country'] ?? null,
                'MobilePhone' => $data['Mobile phone number'] ?? null,
                'Fax' => $data['fax'] ?? null,
                'OtherPhone' => $data['Alternative phone number'] ?? null,
                'AssistantName' => $data['First name of assistant'] ?? null,
                'AssistantPhone' => $data['Phone number of assistant'] ?? null,
                'AccountId' => $accountId,
            ]
        ]);
        return $response->toArray();
    }
}
