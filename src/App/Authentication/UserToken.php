<?php


namespace App\Authentication;


class UserToken implements UserTokenInterface
{
    /**
     * @var UserInterface
     */
    private $user;

    /**
     * UserToken constructor.
     * @param UserInterface|null $user
     */
    public function __construct($user = null)
    {
        $this->user = $user;
    }

    /**
     * Метод возвращает соответствующего юзера, если он есть.
     *
     * @return UserInterface|null
     */
    public function getUser(): ?UserInterface
    {
        return $this->user;
    }

    /**
     * Метод возращает true, если запрос пришел от анонима, иначе false
     *
     * @return bool
     */
    public function isAnonymous()
    {
        return gettype($this->user) === 'NULL';
    }
}