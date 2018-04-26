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
	 * @param mixed $credentials
	 * @return UserTokenInterface
	 */
	public function authenticate($credentials);

	/**
	 * Метод генерирует authentication credentials
	 *
	 * @param UserInterface $user
	 * @return mixed
	 */
	public function generateCredentials(UserInterface $user);
}