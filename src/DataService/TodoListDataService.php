<?php


namespace App\DataService;


use App\Entity\TodoList;
use App\Repository\TodoListRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;

class TodoListDataService
{
    private EntityManagerInterface $entityManager;
    private TodoListRepository $todoListRepository;

    /**
     * TodoListDataService constructor.
     * @param EntityManagerInterface $entityManager
     * @param TodoListRepository $todoListRepository
     */
    public function __construct(EntityManagerInterface $entityManager, TodoListRepository $todoListRepository)
    {
        $this->entityManager = $entityManager;
        $this->todoListRepository = $todoListRepository;
    }

    /**
     * @return TodoList[]
     */
    public function getAll(): array
    {
        return $this->todoListRepository->findAll();
    }

    /**
     * @param TodoList $todoList
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function create(TodoList $todoList): void
    {
        $this->entityManager->persist($todoList);
        $this->entityManager->flush();
    }

    public function getOne(int $id): ?TodoList
    {
        return $this->todoListRepository->find($id);
    }

    /**
     * @throws OptimisticLockException
     */
    public function delete(TodoList $todoList)
    {

        $this->entityManager->remove($todoList);
        $this->entityManager->flush();
    }

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     */
    public function update(TodoList $todoList)
    {
        $this->entityManager->persist($todoList);
        $this->entityManager->flush();
    }
}