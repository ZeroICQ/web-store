<?php


use App\Helpers\StaticPathExtension;
use App\Routing\Router;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class MicroKernel
{
    /**
     * @var ContainerBuilder
     */
    private $container;

    /**
     * @var Router
     */
    private $router;


    public function __construct()
    {
        $this->container = new ContainerBuilder();

        $twig_loader = new Twig_Loader_Filesystem($this->getTemplateDir());
        $staticPath = new StaticPathExtension($this->getStaticDirName());
        $this->container->register('twig', Twig_Environment::class)
            ->setArguments([
                $twig_loader,
                [
                    'debug' => true,
                    'cache' => $this->getCacheDir()
                    //'auto_reload' => true,
                ]
            ])
            ->addMethodCall('addExtension', [$staticPath]);

        $this->router = new Router($this->container);

    }

    /**
     * Get path to templates directory
     * @return string
     */
    public function getTemplateDir(): string
    {
        return __DIR__.'/../src/App/Templates';
    }

    /**
     * @return string
     */
    public function getCacheDir(): string
    {
        return __DIR__.'/../dump/cache';
    }

    /**
     * @return string
     */
    public function getStaticDir(): string
    {
        return __DIR__.'/../static';
    }

    /**
     * @return string
     */
    public function getStaticDirName(): string
    {
        return basename($this->getStaticDir());
    }

    /**
     * @param string $URI
     * @return string
     */
    public function handleRequest($URI) : string
    {
        return $this->router->handleUri($URI);
    }
}