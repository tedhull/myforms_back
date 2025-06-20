<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class AuthController extends AbstractController
{
    #[Route('/auth/register', name: 'app_auth_register', methods: ['POST'])]
    public function register(Request $request, UserPasswordHasherInterface $hasher, EntityManagerInterface $em, ValidatorInterface $validator): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $user = new User();
        $user->setEmail($data['email']);
        $user->setPassword($hasher->hashPassword($user, $data['password']));
        if (isset($data['roles'])) $user->setRoles($data['roles']);

        $errors = $validator->validate($user);
        if (count($errors) > 0) {
            $errorsString = (string)$errors;
            return new JsonResponse($errorsString, 400);
        }
        $em->persist($user);
        $em->flush();
        return $this->json([
            'message' => 'User created successfully',
            'user' => $user]);
    }

    /**
     * @throws \Exception
     */
    #[Route('/api/login_check', name: 'api_login_check', methods: ['POST'])]
    public function profile(): JsonResponse
    {
        throw new \Exception('Should not be reached - handled by LexikJWTAuthenticationBundle.');
    }

    #[Route('api/user', name: 'app_user', methods: ['GET'])]
    public function User(): JsonResponse
    {
        $user = $this->getUser();

        return $this->json([
            'message' => 'This is a protected route',
            'user' => $user ? $user->getUserIdentifier() : null,
            'roles' => $user ? $user->getRoles() : [],
        ]);
    }
}
