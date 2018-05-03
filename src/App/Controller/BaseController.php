<?php

namespace App\Controller;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Twig_Environment;
use Twig_Loader_Filesystem;

abstract class BaseController
{
    /**
     * @var ContainerBuilder
     */
    protected $container;

    public function __construct(ContainerBuilder $container)
    {
        $this->container = $container;
    }

}