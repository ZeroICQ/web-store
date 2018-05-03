<?php

namespace App\Controller;

use Twig_Environment;
use Twig_Loader_Filesystem;

abstract class BaseController
{
    /**
     * @var Twig_Environment
     */
    protected $twig;

    public function __construct()
    {
        $loader = new Twig_Loader_Filesystem(__DIR__.'/../Templates');
        $this->twig = new Twig_Environment($loader, array(
            'cache' => __DIR__.'/../../../dump/cache',
        ));
    }

}