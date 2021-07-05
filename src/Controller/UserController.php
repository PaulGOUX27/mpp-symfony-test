<?php

namespace App\Controller;

use App\Service\UserService;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UserController extends AbstractController
{
    private UserService $userService;


    /**
     * UserController constructor.
     * @param UserService $userService
     */
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function indexAction(): JsonResponse
    {
        $users = $this->userService->getAll();
        $data = array();
        foreach ($users as $user) {
            $data[] = $user->toJson();
        }
        return new JsonResponse($data, Response::HTTP_OK);
    }

    public function createAction(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);
        try {
            $this->userService->create($data);
        } catch (Exception) {
            return new Response(null, Response::HTTP_BAD_REQUEST);
        }

        return new Response(null, Response::HTTP_CREATED);
    }


    public function getOneAction(int $id): JsonResponse
    {
        $user = $this->userService->getOne($id);
        if(!$user) {
            return new JsonResponse(null, Response::HTTP_NOT_FOUND);
        }
        return new JsonResponse($user->toJSON(), Response::HTTP_OK);
    }

    public function deleteAction(int $id): Response
    {
        try {
            $this->userService->delete($id);
        } catch (Exception) {
            return new Response(null, Response::HTTP_BAD_REQUEST);
        }
        return new Response(null, Response::HTTP_NO_CONTENT);
    }

    public function updateAction(Request $request, int $id): Response
    {
        $data = json_decode($request->getContent(), true);
        try {
            $this->userService->update($id, $data);
        } catch (NotFoundHttpException) {
            return new Response(null, Response::HTTP_NOT_FOUND);
        }

        return new Response(null, Response::HTTP_NO_CONTENT);
    }
}
