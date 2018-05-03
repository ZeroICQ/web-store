<?php

namespace App\Routing;


use App\Controller\LoginController;

class Router
{
    /**
     * @string $URI
     * @return string
     */
    public static function handleUri($URI): string
    {
        $URI = explode('?', $URI)[0];

        switch ($URI) {
            case '/':
            case '/signIn':
                return (new LoginController())->signInAction();
            default:
                header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found", true, 404);
                return '404';
        }
    }
}