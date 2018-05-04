<?php

namespace App\Authentication\Encoder;

/**
 * Интерфейс предоставляет услуги по шифрованию пароля
 *
 * Interface UserPasswordEncoderInterface
 * @package App\Authentication\Encoder
 */
interface UserPasswordEncoderInterface
{
	/**
	 * Метод принимает чистый пароль и соль (опциональна) и возвращает в зашифрованном виде.
	 *
	 * @param string $rawPassword
	 * @return string
	 */
	static public function encodePassword(string $rawPassword): string;
}