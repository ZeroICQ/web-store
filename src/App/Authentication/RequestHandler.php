<?php

namespace App\Authentication;


class RequestHandler
{
    /**
     * @param $name
     * @return string
     */
    public static function handle($name):string
    {
        return "Hello, {$name}";
    }
}