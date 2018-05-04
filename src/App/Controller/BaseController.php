<?php

namespace App\Controller;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpFoundation\Request;
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
     * BaseController constructor.
     * @param ContainerBuilder $container
     * @param Request $request
     */
    public function __construct(ContainerBuilder $container, Request $request)
    {
        $this->container = $container;
        $this->request = $request;
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

}