<?php

namespace App\Routing;


use App\Controller\LoginController;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpFoundation\Request;

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
     * @param Request $URI
     * @return string
     */
    public function handleUri(Request $request): string
    {
        $URI = explode('?', $request->getRequestUri())[0];

        switch ($URI) {
            case '/':
            case '/signIn':
                return (new LoginController($this->container, $request))->signInAction();
            default:
                header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found", true, 404);
                return '404';
        }
    }
}