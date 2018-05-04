<?php


namespace App\Authentication;


class User implements UserInterface
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $login;

    /**
     * @var string
     */
    private $password;

    /**
     * @var string
     */
    private $salt;


    /**
     * User constructor.
     * @param int $id
     * @param string $login
     * @param string $password
     * @param string $salt
     */
    public function __construct(int $id, string $login, string $password, string $salt)
    {
        $this->id = $id;
        $this->login = $login;
        $this->password = $password;
        $this->salt = $salt;
    }


    /**
     * Метод возвращает идентификационную информацию пользователя (первичный ключ в БД пользователей приложения)
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Метод возвращает логин пользователя. Логин является уникальным свойством.
     *
     * @return string
     */
    public function getLogin(): string
    {
        return $this->login;
    }

    /**
     * Метод возвращает пароль пользователя. Пароль возвращается в зашифрованном виде.
     *
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * Метод возвращает соль, которая участвовала при построении пароля
     *
     * @return string|null
     */
    public function getSalt(): ?string
    {
        return $this->salt;
    }
}