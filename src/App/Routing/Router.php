<?php

namespace App\Routing;


use App\Api\UserInfoApiController;
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
                return $this->container->get(LoginController::class)->signInAction();
            case '/register':
                return $this->container->get(LoginController::class)->registerAction();
            case '/logout':
                return $this->container->get(LoginController::class)->logoutAction();
            case '/profile':
                return $this->container->get(ProfileController::class)->viewProfileAction();
            case '/edit':
                return $this->container->get(ProfileController::class)->editProfileAction();
            case '/api/userinfo.json':
                return $this->container->get(UserInfoApiController::class)->getInfoAction();
            case '/api/updateUser':
                return $this->container->get(UserInfoApiController::class)->updateUser();
            default:
                return new Response("404", 404);
        }
    }
}