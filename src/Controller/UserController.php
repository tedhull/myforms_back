<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

final class UserController extends AbstractController
{
    #[Route('/api/user/forms', name: 'user_view', methods: ['GET'])]
    function getForms(SerializerInterface $serializer): JsonResponse
    {
        $user = $this->getUser();
        $forms = $user->getForms();
        $json = $serializer->serialize($forms, 'json', ['groups' => ['form:list']]);
        return new JsonResponse($json);
    }

    #[Route('/api/user/templates')]
    public function getTemplates(SerializerInterface $serializer): JsonResponse
    {
        $user = $this->getUser();
        $templates = $user->getTemplates();
        $json = $serializer->serialize($templates, 'json', ['groups' => ['template:list']]);
        return new JsonResponse($json);
    }

    #[Route('/api/user/list', name: 'users', methods: ['GET'])]
    public function getUsers(SerializerInterface $serializer, Request $request, EntityManagerInterface $emi): JsonResponse
    {
        $users = $emi->getRepository(User::class)->findAll();
        $json = $serializer->serialize($users, 'json', ['groups' => ['user:list']]);
        return new JsonResponse($json);
    }

    #[Route('api/user/promote', name: 'user_promote', methods: ['POST'])]
    public function promote(Request $request, EntityManagerInterface $emi)
    {
        $data = json_decode($request->getContent(), true);
        $ids = $data['ids'];

        foreach ($ids as $id) {
            $user = $emi->getRepository(User::class)->find($id);
            $roles = $user->getRoles();
            $user->setRoles($this->addRole($roles, "ROLE_ADMIN"));
            $emi->persist($user);
        }
        $emi->flush();
        return new JsonResponse([], 200);
    }

    #[Route('api/user/restrict', name: 'user_restrict', methods: ['POST'])]
    public function restrict(Request $request, EntityManagerInterface $emi): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $ids = $data['ids'];
        foreach ($ids as $id) {
            $user = $emi->getRepository(User::class)->find($id);
            $roles = $user->getRoles();
            $user->setRoles($this->removeRole($roles, "ROLE_ADMIN"));
            $emi->persist($user);
        }
        $emi->flush();
        return new JsonResponse([], 200);
    }

    #[Route('/api/user/remove', name: 'user_delete', methods: ['DELETE'])]
    public function remove(Request $request, SerializerInterface $serializer, EntityManagerInterface $emi): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $ids = $data['ids'];
        foreach ($ids as $id) {
            $user = $emi->getRepository(User::class)->find($id);
            $emi->remove($user);
        }
        $emi->flush();
        $json = $serializer->serialize($ids, 'json');
        return new JsonResponse($json, 200);
    }

    #[Route('api/user/ban', name: 'user_ban', methods: ['POST'])]
    public function ban(Request $request, EntityManagerInterface $emi): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $ids = $data['ids'];
        foreach ($ids as $id) {
            $user = $emi->getRepository(User::class)->find($id);
            $roles = $user->getRoles();
            $user->setRoles($this->addRole($roles, "ROLE_BANNED"));
            $emi->persist($user);
        }
        $emi->flush();
        return new JsonResponse([], 200);
    }

    #[Route('/api/user/unban', name: 'user_unban', methods: ['POST'])]
    public function unban(Request $request, SerializerInterface $serializer, EntityManagerInterface $emi): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $ids = $data['ids'];
        foreach ($ids as $id) {
            $user = $emi->getRepository(User::class)->find($id);
            $roles = $user->getRoles();
            $user->setRoles($this->removeRole($roles, "ROLE_BANNED"));
            $emi->persist($user);
            $json = $serializer->serialize($user, 'json', ['groups' => ['user:list']]);
        }
        $emi->flush();
        return new JsonResponse($json, 200);
    }

    private function addRole($roles, $role)
    {
        if (!in_array($role, $roles)) {
            $roles[] = $role;
        }
        return $roles;
    }

    private function removeRole($roles, $role)
    {
        if (in_array($role, $roles)) {
            $roles = array_filter($roles, fn($r) => $r !== $role);
        }
        return $roles;

    }
}
