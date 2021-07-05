<?php


namespace App\Service;


use App\DataService\TaskDataService;
use App\DataService\TodoListDataService;
use App\Entity\Task;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Exception;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class TaskService
{
    private TaskDataService $taskDataService;

    private ValidatorInterface $validator;

    private TodoListDataService $todoListDataService;

    /**
     * TaskService constructor.
     * @param TaskDataService $taskDataService
     * @param ValidatorInterface $validator
     * @param TodoListDataService $todoListDataService
     */
    public function __construct(TaskDataService $taskDataService, ValidatorInterface $validator, TodoListDataService $todoListDataService)
    {
        $this->taskDataService = $taskDataService;
        $this->validator = $validator;
        $this->todoListDataService = $todoListDataService;
    }

    public function getAll(): array
    {
        return $this->taskDataService->getAll();
    }

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     * @throws Exception
     */
    public function create(mixed $data)
    {
        $task = new Task();
        $task->setMessage($data["message"] ?? null);
        $task->setDone($data["done"] ?? false);
        $task->setTodoList($this->todoListDataService->getOne($data["todoListId"]));

        $errors = $this->validator->validate($task);
        if(count($errors) > 0) {
            throw new Exception();
        }

        $this->taskDataService->create($task);
    }

    public function getOne(int $id): ?Task
    {
        return $this->taskDataService->getOne($id);
    }

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     */
    public function delete(int $id)
    {
        $task = $this->getOne($id);
        if ($task) {
            $this->taskDataService->delete($task);
        }
    }

    /**
     * @throws Exception
     */
    public function update(int $id, mixed $data)
    {
        $task = $this->getOne($id);
        if (!$task) {
            throw new NotFoundHttpException();
        }
        $task->setMessage($data["message"] ?? $task->getMessage());
        $task->setDone($data["done"] ?? $task->getDone());
        $task->setTodoList($this->todoListDataService->getOne($data["todoListId"]) ?? $task->getTodoList());

        $errors = $this->validator->validate($task);
        if(count($errors) > 0) {
            throw new Exception();
        }

        $this->taskDataService->update($task);
    }


}