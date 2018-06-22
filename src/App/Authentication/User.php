<?php


namespace App\Authentication;


use App\Authentication\Repository\UserInfoRepositoryInterface;

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
     * @var UserInfoRepositoryInterface
     */
    private $userInfoRepository;

    /**
     * User constructor.
     * @param int|null $id
     * @param string $login
     * @param string $password
     * @param UserInfoInterface|null $userInfo
     */
    public function __construct(?int $id, string $login, string $password, UserInfoRepositoryInterface $userInfoRepository)
    {
        $this->id = $id;
        $this->login = strlen($login) > 0 ? strtolower($login) : null;
        $this->password = strlen($password) > 0 ? $password : null;
        $this->userInfoRepository = $userInfoRepository;
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
        if (!$this->userInfo) {
            $this->userInfo = $this->userInfoRepository->getInfo($this->id);
        }
        return $this->userInfo;
    }


    /**
     * @return array
     */
    public function toArray(): array
    {
        $arr = [
            'id'    => $this->id,
            'login' => $this->login,
            'biography'  => $this->getUserInfo()->getBiography(),
            'firstName'  => $this->getUserInfo()->getFirstName(),
            'secondName' => $this->getUserInfo()->getSecondName(),
            'workPlace'  => $this->getUserInfo()->getWorkPlace()
        ];

//        if ($this->userInfo) {
//            $arr = array_merge($arr, [
//                'biography'  => $this->getUserInfo()->getBiography(),
//                'firstName'  => $this->getUserInfo()->getFirstName(),
//                'secondName' => $this->getUserInfo()->getSecondName(),
//                'workPlace'  => $this->getUserInfo()->getWorkPlace()
//            ]);
//        }
        return $arr;
    }

}