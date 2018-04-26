<?php

namespace App\Authentication;

/**
 * Данный класс хранит информацию о пользователе и участвует в процессах аутентификации.
 *
 * Interface UserTokenInterface
 * @package App\Authentication
 */
interface UserTokenInterface
{
	/**
	 * Метод возвращает соответствующего юзера, если он есть.
	 *
	 * @return UserInterface|null
	 */
	public function getUser(): ?UserInterface;

	/**
	 * Метод возращает true, если запрос пришел от анонима, иначе false
	 *
	 * @return bool
	 */
	public function isAnonymous();
}