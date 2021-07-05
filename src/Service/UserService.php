<?php


namespace App\Service;


use App\DataService\UserDataService;
use App\Entity\User;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Exception;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserService
{
    private UserDataService $userDataService;

    private ValidatorInterface $validator;

    private UserPasswordEncoderInterface $passwordEncoder;

    /**
     * UserService constructor.
     * @param UserDataService $userDataService
     * @param ValidatorInterface $validator
     * @param UserPasswordEncoderInterface $passwordEncoder
     */
    public function __construct(UserDataService $userDataService, ValidatorInterface $validator, UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->userDataService = $userDataService;
        $this->validator = $validator;
        $this->passwordEncoder = $passwordEncoder;
    }

    public function getAll(): array
    {
        return $this->userDataService->getAll();
    }

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     * @throws Exception
     */
    public function create(mixed $data)
    {
        $user = new User();
        $user->setEmail($data["email"]);
        $user->setPassword($this->passwordEncoder->encodePassword($user, $data["password"]));

        $errors = $this->validator->validate($user);
        if(count($errors) > 0) {
            throw new Exception();
        }

        $this->userDataService->create($user);
    }

    public function getOne(int $id): ?User
    {
        return $this->userDataService->getOne($id);
    }

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     */
    public function delete(int $id)
    {
        $user = $this->getOne($id);
        if ($user) {
            $this->userDataService->delete($user);
        }
    }

    /**
     * @throws Exception
     */
    public function update(int $id, mixed $data)
    {
        $user = $this->getOne($id);
        if (!$user) {
            throw new NotFoundHttpException();
        }
        if(isset($data["password"])) {
            $user->setPassword($this->passwordEncoder->encodePassword($user, $data["password"]));
        }

        $errors = $this->validator->validate($user);
        if(count($errors) > 0) {
            throw new Exception();
        }

        $this->userDataService->update($user);
    }


}