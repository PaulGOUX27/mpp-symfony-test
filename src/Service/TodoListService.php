<?php


namespace App\Service;


use App\DataService\TodoListDataService;
use App\Entity\TodoList;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Exception;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class TodoListService
{

    private TodoListDataService $todoListDataService;

    private ValidatorInterface $validator;

    private UserService $userService;

    /**
     * TodoListService constructor.
     * @param TodoListDataService $todoListDataService
     * @param ValidatorInterface $validator
     * @param UserService $userService
     */
    public function __construct(TodoListDataService $todoListDataService, ValidatorInterface $validator, UserService $userService)
    {
        $this->todoListDataService = $todoListDataService;
        $this->validator = $validator;
        $this->userService = $userService;
    }


    public function getAll(): array
    {
        return $this->todoListDataService->getAll();
    }

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     * @throws Exception
     */
    public function create(mixed $data)
    {
        $todoList = new TodoList();
        //TODO get connected User
        $todoList->setUser($this->userService->getOne(1));
        $todoList->setName($data["name"]);

        $errors = $this->validator->validate($todoList);
        if(count($errors) > 0) {
            throw new Exception();
        }

        $this->todoListDataService->create($todoList);
    }

    public function getOne(int $id): ?TodoList
    {
        return $this->todoListDataService->getOne($id);
    }

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     */
    public function delete(int $id)
    {
        $todoList = $this->getOne($id);
        if ($todoList) {
            $this->todoListDataService->delete($todoList);
        }
    }

    /**
     * @throws Exception
     */
    public function update(int $id, mixed $data)
    {
        $todoList = $this->getOne($id);
        if (!$todoList) {
            throw new NotFoundHttpException();
        }
        $todoList->setName($data["name"]);

        $errors = $this->validator->validate($todoList);
        if(count($errors) > 0) {
            throw new Exception();
        }

        $this->todoListDataService->update($todoList);
    }
}