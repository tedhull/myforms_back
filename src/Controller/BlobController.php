<?php

namespace App\Controller;

use Aws\S3\Exception\S3Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Aws\S3\S3Client;

require dirname(__DIR__) . '/../vendor/autoload.php';

final class BlobController extends AbstractController
{
    #[Route('/blob', name: 'app_blob')]
    public function index(): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/BlobController.php',
        ]);
    }

    #[Route('/api/blob/presign', name: 'app_blob_presign', methods: ['POST'])]
    public function presign(Request $request): JsonResponse
    {
        $filename = $request->request->get('filename');
        if (!$filename) return new JsonResponse(['error' => 'No filename provided'], 400);
        $s3_client = $this->configureS3();
        $bucket_name = $_ENV['CF_BUCKET'];
        $key = uniqid();
        $cmd = $s3_client->getCommand('PutObject', [
            'Bucket' => $bucket_name,
            'Key' => $key
        ]);

        $request = $s3_client->createPresignedRequest($cmd, '+1 hour');
        return new JsonResponse([
            'url' => (string)$request->getUri(),
            'key' => $key,
        ]);
    }

    #[Route('/blob/push', name: 'app_blob_create', methods: ['POST'])]
    public function push(Request $request): JsonResponse
    {
        $bucket = $_ENV['CF_BUCKET'];

        $s3 = $this->configureS3();
        $files = $request->files->get('files');
        try {
            $result = $s3->putObject([
                'Bucket' => $bucket,
                'Key' => 'test/hello.txt',
                'Body' => 'this is the body!',
                'ACL' => 'public-read',
                'ContentType' => 'text/plain',
            ]);

            return new JsonResponse([
                'url' => $result['ObjectURL'],
            ], 200);

        } catch (S3Exception $e) {
            return new JsonResponse([
                'error' => $e->getMessage(),
            ], 400);
        }
    }

    function configureS3(): S3Client
    {
        $accountId = $_ENV['CF_ACCOUNT_ID'];
        $accessKey = $_ENV['CF_ACCESS_KEY_ID'];
        $secretKey = $_ENV['CF_SECRET_ACCESS_KEY'];
        return new S3Client([
            'version' => 'latest',
            'region' => 'auto',
            'endpoint' => "https://{$accountId}.r2.cloudflarestorage.com",
            'use_path_style_endpoint' => true,
            'credentials' => [
                'key' => $accessKey,
                'secret' => $secretKey,
            ],
        ]);
    }
}
