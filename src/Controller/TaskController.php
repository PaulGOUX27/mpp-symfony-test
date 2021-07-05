<?php


namespace App\Controller;


use App\Service\TaskService;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class TaskController extends AbstractController
{

    private TaskService $taskService;

    /**
     * TaskController constructor.
     * @param TaskService $taskService
     */
    public function __construct(TaskService $taskService)
    {
        $this->taskService = $taskService;
    }

    public function indexAction(): JsonResponse
    {
        $tasks = $this->taskService->getAll();
        $data = array();
        foreach ($tasks as $task) {
            $data[] = $task->toJson();
        }
        return new JsonResponse($data, Response::HTTP_OK);
    }

    public function createAction(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);
        try {
            $this->taskService->create($data);
        } catch (Exception) {
            return new Response(null, Response::HTTP_BAD_REQUEST);
        }

        return new Response(null, Response::HTTP_CREATED);
    }


    public function getOneAction(int $id): JsonResponse
    {
        $task = $this->taskService->getOne($id);
        if(!$task) {
            return new JsonResponse(null, Response::HTTP_NOT_FOUND);
        }
        return new JsonResponse($task->toJSON(), Response::HTTP_OK);
    }

    public function deleteAction(int $id): Response
    {
        try {
            $this->taskService->delete($id);
        } catch (Exception) {
            return new Response(null, Response::HTTP_BAD_REQUEST);
        }
        return new Response(null, Response::HTTP_NO_CONTENT);
    }

    public function updateAction(Request $request, int $id): Response
    {
        $data = json_decode($request->getContent(), true);
        try {
            $this->taskService->update($id, $data);
        } catch (NotFoundHttpException) {
            return new Response(null, Response::HTTP_NOT_FOUND);
        }

        return new Response(null, Response::HTTP_NO_CONTENT);
    }
}