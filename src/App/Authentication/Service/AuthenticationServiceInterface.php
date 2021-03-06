<?php

namespace App\Authentication\Service;

use App\Authentication\UserTokenInterface;
use App\Authentication\UserInterface;

/**
 * Контракт представляет услуги по аутентификации и идентификации клиентов.
 *
 * Пример аутентификации:
 * $authService = new AuthenticationService(..);
 * $userToken = $authService->authenticate($request->getCookie('auth_cookie'));
 *
 * if ($userToken->isAnonymous()) { ...
 * } else {
 *      $user = $userToken->getUser();
 *      ...
 * }
 *
 * ###
 *
 * Пример проставления аутентификационной информации:
 * $response->setCookie('auth_cookie', $authService->generateCredentials($user));
 *
 * Interface AuthenticationServiceInterface
 * @package App\Authentication\Service
 */
interface AuthenticationServiceInterface
{
    /**
     * Метод аутентифицирует пользователя на основании authentication credentials запроса
     *
     * @param UserInterface $credentials
     * @return UserTokenInterface
     */
    public function authenticate($credentials);

    /**
     * Метод генерирует authentication credentials
     *
     * @param string $login
     * @param string $password
     * @return mixed
     */
    public function generateCredentials(string $login, string $password);

    /**
     * Метод генерирует authentication credentials без проверки пароля
     * @param string $login
     * @return mixed
     */
    public function regenerateCredentials(string $login);
}