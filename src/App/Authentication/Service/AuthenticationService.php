<?php


namespace App\Authentication\Service;


use App\Authentication\UserInterface;
use App\Authentication\UserToken;
use App\Authentication\UserTokenInterface;
use App\ORM\EntityManager;

class AuthenticationService implements AuthenticationServiceInterface
{
    /**
     * @var EntityManager
     */
    private $em;

    public function __construct($entityManager)
    {
        $this->em = $entityManager;
    }


    /**
     * Метод аутентифицирует пользователя на основании authentication credentials запроса
     *
     * @param mixed $credentials
     * @return UserTokenInterface
     */
    public function authenticate($credentials) : UserTokenInterface
    {
//        $this->em->getRepository('user')->findById(1);
        return new UserToken($this->em->getRepository('user')->findById(1));
    }

    /**
     * Метод генерирует authentication credentials
     *
     * @param UserInterface $user
     * @return mixed
     */
    public function generateCredentials(UserInterface $user)
    {
        // TODO: Implement generateCredentials() method.
    }
}