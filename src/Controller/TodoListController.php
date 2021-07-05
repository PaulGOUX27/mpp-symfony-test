<?php


namespace App\Controller;


use App\Service\TodoListService;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class TodoListController extends AbstractController
{

    private TodoListService $todoListService;

    /**
     * TodoListController constructor.
     * @param TodoListService $todoListService
     */
    public function __construct(TodoListService $todoListService)
    {
        $this->todoListService = $todoListService;
    }

    public function indexAction(): JsonResponse
    {
        $todoLists = $this->todoListService->getAll();
        $data = array();
        foreach ($todoLists as $todoList) {
            $data[] = $todoList->toJson();
        }
        return new JsonResponse($data, Response::HTTP_OK);
    }

    public function createAction(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);
        try {
            $this->todoListService->create($data);
        } catch (Exception) {
            return new Response(null, Response::HTTP_BAD_REQUEST);
        }

        return new Response(null, Response::HTTP_CREATED);
    }


    public function getOneAction(int $id): JsonResponse
    {
        $todoList = $this->todoListService->getOne($id);
        if(!$todoList) {
            return new JsonResponse(null, Response::HTTP_NOT_FOUND);
        }
        return new JsonResponse($todoList->toJSON(), Response::HTTP_OK);
    }

    public function deleteAction(int $id): Response
    {
        try {
            $this->todoListService->delete($id);
        } catch (Exception) {
            return new Response(null, Response::HTTP_BAD_REQUEST);
        }
        return new Response(null, Response::HTTP_NO_CONTENT);
    }

    public function updateAction(Request $request, int $id): Response
    {
        $data = json_decode($request->getContent(), true);
        try {
            $this->todoListService->update($id, $data);
        } catch (NotFoundHttpException) {
            return new Response(null, Response::HTTP_NOT_FOUND);
        }

        return new Response(null, Response::HTTP_NO_CONTENT);
    }
}