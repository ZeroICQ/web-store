<?php


use App\Authentication\Service\AuthenticationService;
use App\Helpers\StaticPathExtension;
use App\ORM\EntityManager;
use App\Routing\Router;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

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
        //di
        $this->container = new ContainerBuilder();
        //twig
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
        //db
        $this->container->register('db', mysqli::class)
            ->setArguments([
                'mysql',
                'app',
                'app',
                'app'
            ]);
        //repository manager
        $this->container->register('entityManager', EntityManager::class)
            ->setArguments([new Reference('db')]);

        //auth service
        $this->container->register('auth', AuthenticationService::class)
            ->setArguments([new Reference('entityManager')]);

        //router
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