<?php

namespace App\Routing;


use App\Controller\LoginController;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class Router
{
    /**
     * @var ContainerBuilder
     */
    private $container;

    public function __construct(ContainerBuilder $container)
    {
        $this->container = $container;
    }

    /**
     * @string $URI
     * @return string
     */
    public function handleUri($URI): string
    {
        $URI = explode('?', $URI)[0];

        switch ($URI) {
            case '/':
            case '/signIn':
                return (new LoginController($this->container))->signInAction();
            default:
                header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found", true, 404);
                return '404';
        }
    }
}