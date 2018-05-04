<?php


namespace App\Authentication\Service;


use App\Authentication\UserInterface;
use App\Authentication\UserToken;
use App\Authentication\UserTokenInterface;
use App\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Session\Session;

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
     * @param Session $session
     * @return UserTokenInterface
     */
    public function authenticate(Session $session) : UserTokenInterface
    {
        $login = $session->get('login');
        if (!$login) {
            return new UserToken(null);
        }

        $user = $this->em->getRepository('user')->findByLogin($login);
        return new UserToken($user);
    }

    /**
     * Метод генерирует authentication credentials
     *
     * @param UserInterface|null $user
     * @param Session $session
     * @return mixed
     */
    public function generateCredentials(?UserInterface $user, Session $session)
    {
        $session->invalidate();
        if (!$user) {
            return;
        }

        $session->set('login', $user->getLogin());
    }
}