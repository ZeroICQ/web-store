<?php

namespace App\Routing;


use App\Controller\LoginController;
use App\Controller\ProfileController;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

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
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function handleUri(Request $request): Response
    {
        $URI = explode('?', $request->getRequestUri())[0];

        //TODO: get rid of copypaste

        switch ($URI) {
            case '/':
            case '/signIn':
                return (new LoginController($this->container, $request))->signInAction();
            case '/register':
                return (new LoginController($this->container, $request))->registerAction();
            case '/logout':
                return (new LoginController($this->container, $request))->logoutAction();
            case '/profile':
                return (new ProfileController($this->container, $request))->profileAction();
            default:
                return new Response("404", 404);
        }
    }
}