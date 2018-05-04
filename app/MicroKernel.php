<?php


use App\Authentication\Service\AuthenticationService;
use App\Twig\StaticPathExtension;
use App\ORM\EntityManager;
use App\Routing\Router;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;

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

        //session
        $this->container->register('session', Session::class)
            ->addMethodCall('start');

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
     * @param Request $request
     * @return Response
     */
    public function handleRequest(Request $request) : Response
    {
        return $this->router->handleUri($request);
    }
}