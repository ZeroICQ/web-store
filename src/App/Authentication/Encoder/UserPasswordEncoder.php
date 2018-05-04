<?php


namespace App\Authentication\Encoder;


class UserPasswordEncoder implements  UserPasswordEncoderInterface
{

    /**
     * Метод принимает чистый пароль и соль (опциональна) и возвращает в зашифрованном виде.
     *
     * @param string $rawPassword
     * @return string
     */
    static public function encodePassword(string $rawPassword): string
    {
        return password_hash($rawPassword, PASSWORD_BCRYPT);
    }
}