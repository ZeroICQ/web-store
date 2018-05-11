<?php

namespace App\Controller;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Twig_Environment;
use Twig_Loader_Filesystem;

abstract class BaseController
{
    /**
     * @var ContainerBuilder
     */
    protected $container;

    /**
     * @var Request
     */
    protected $request;

    /**
     * @var Response
     */
    protected $response;

    /**
     * BaseController constructor.
     * @param ContainerBuilder $container
     * @param Request $request
     */
    public function __construct(ContainerBuilder $container, Request $request)
    {
        $this->container = $container;
        $this->request = $request;

        $this->response = new Response();
    }

    /**
     * @return bool
     */
    public function isPost(): bool
    {
        return $this->request->isMethod('POST');
    }

    /**
     * @return bool
     */
    public function isGet(): bool
    {
        return $this->request->isMethod('GET');
    }

    /**
     * @param string $templateName
     * @param array $params
     * @return bool
     */
    protected function render(string $templateName, array $params = []): bool
    {
        $this->response->setContent($this->container->get(Twig_Environment::class)->render($templateName, $params));
        return true;
    }
}