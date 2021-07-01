<?php

namespace App\Controller;

use App\DataService\UserDataService;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    private UserDataService $userDataService;

    /**
     * UserController constructor.
     * @param UserDataService $userDataService
     */
    public function __construct(UserDataService $userDataService)
    {
        $this->userDataService = $userDataService;
    }

    public function indexAction(UserRepository $userDataService): Response
    {
        $users = $userDataService->findAll();
        return new JsonResponse($users, Response::HTTP_OK);
    }
}
