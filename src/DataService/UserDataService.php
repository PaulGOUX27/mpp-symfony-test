<?php

namespace App\DataService;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserDataService extends AbstractController
{

    private UserRepository $userRepository;

    /**
     * UserDataService constructor.
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }


    /**
     * @return User[]
     */
    public function getAll(): array
    {
        return $this->userRepository->findAll();
    }
}
