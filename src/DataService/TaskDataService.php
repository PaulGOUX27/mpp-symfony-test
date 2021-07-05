<?php


namespace App\DataService;


use App\Entity\Task;
use App\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;

class TaskDataService
{
    private EntityManagerInterface $entityManager;
    private TaskRepository $taskRepository;

    /**
     * TaskDataService constructor.
     * @param EntityManagerInterface $entityManager
     * @param TaskRepository $taskRepository
     */
    public function __construct(EntityManagerInterface $entityManager, TaskRepository $taskRepository)
    {
        $this->entityManager = $entityManager;
        $this->taskRepository = $taskRepository;
    }

    /**
     * @return Task[]
     */
    public function getAll(): array
    {
        return $this->taskRepository->findAll();
    }

    /**
     * @param Task $task
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function create(Task $task): void
    {
        $this->entityManager->persist($task);
        $this->entityManager->flush();
    }

    public function getOne(int $id): ?Task
    {
        return $this->taskRepository->find($id);
    }

    /**
     * @throws OptimisticLockException
     */
    public function delete(Task $task)
    {

        $this->entityManager->remove($task);
        $this->entityManager->flush();
    }

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     */
    public function update(Task $task)
    {
        $this->entityManager->persist($task);
        $this->entityManager->flush();
    }
}