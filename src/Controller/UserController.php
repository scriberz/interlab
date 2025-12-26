<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

class UserController extends AbstractController
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @Route("/api/users", methods={"POST"})
     */
    public function createUser(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $user = new User();
        $user->setUsername($data['username']);
        $user->setPassword($data['password']); // В реальном приложении пароли нужно хэшировать!

        $this->userRepository->save($user);

        return new JsonResponse($user, Response::HTTP_CREATED);
    }

    /**
     * @Route("/api/users/{id}", methods={"PUT"})
     */
    public function updateUser(int $id, Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $user = $this->userRepository->find($id);

        if (!$user) {
            return new JsonResponse(['message' => 'User not found'], Response::HTTP_NOT_FOUND);
        }

        if (isset($data['username'])) {
            $user->setUsername($data['username']);
        }

        $this->userRepository->save($user);

        return new JsonResponse($user);
    }

    /**
     * @Route("/api/users/{id}", methods={"DELETE"})
     */
    public function deleteUser(int $id): JsonResponse
    {
        $user = $this->userRepository->find($id);

        if (!$user) {
            return new JsonResponse(['message' => 'User not found'], Response::HTTP_NOT_FOUND);
        }

        $this->userRepository->remove($user);

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * @Route("/api/login", methods={"POST"})
     */
    public function login(Request $request): JsonResponse
    {
        // Здесь можно реализовать логику авторизации
        return new JsonResponse(['message' => 'Login successful']);
    }

    /**
     * @Route("/api/users/{id}", methods={"GET"})
     */
    public function getUser(int $id): JsonResponse
    {
        $user = $this->userRepository->find($id);

        if (!$user) {
            return new JsonResponse(['message' => 'User not found'], Response::HTTP_NOT_FOUND);
        }

        return new JsonResponse($user);
    }
}
