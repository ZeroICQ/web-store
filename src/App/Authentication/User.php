<?php


namespace App\Authentication;


class User implements UserInterface
{
    public const authCookieName = 'auth_cookie';

    /**
     * @var int|null
     */
    private $id;

    /**
     * @var string|null
     */
    private $login;

    /**
     * @var string|null
     */
    private $password;
    /**
     * @var UserInfoInterface|null
     */
    private $userInfo;

    /**
     * User constructor.
     * @param int|null $id
     * @param string $login
     * @param string $password
     * @param UserInfoInterface|null $userInfo
     */
    public function __construct(?int $id, string $login, string $password, UserInfoInterface $userInfo = null)
    {
        $this->id = $id;
        $this->login = strlen($login) > 0 ? strtolower($login) : null;
        $this->password = strlen($password) > 0 ? $password : null;
        $this->userInfo = $userInfo;
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
    public function getLogin(): ?string
    {
        return $this->login;
    }


    /**
     * Метод возвращает пароль пользователя. Пароль возвращается в зашифрованном виде.
     *
     * @return string
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * @return UserInfoInterface|null
     */
    public function getUserInfo(): ?UserInfoInterface
    {
        return $this->userInfo;
    }

    /**
     * Specify data which should be serialized to JSON
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    public function jsonSerialize()
    {
        $json = [
            'id'    => $this->id,
            'login' => $this->login
        ];

        if ($this->userInfo) {
            $json = array_merge($json, [
                'biography'  => $this->userInfo->getBiography(),
                'firstName'  => $this->userInfo->getFirstName(),
                'secondName' => $this->userInfo->getSecondName(),
                'workPlace'  => $this->userInfo->getWorkPlace()
            ]);
        }
        return $json;
    }

}