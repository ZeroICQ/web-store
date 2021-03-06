<?php


use App\Api\UserInfoApiController;
use App\Authentication\Repository\UserInfoRepository;
use App\Authentication\Repository\UserRepository;
use App\Authentication\Service\AuthenticationService;
use App\Controller\LoginController;
use App\Controller\ProfileController;
use App\ORM\DB;
use App\Twig\StaticPathExtension;
use App\Routing\Router;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

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


    /**
     * MicroKernel constructor.
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        //di
        $this->container = new ContainerBuilder();

        //twig
        $twig_loader = new Twig_Loader_Filesystem($this->getTemplateDir());
        $staticPath = new StaticPathExtension($this->getStaticDirName());
        $this->container->register(Twig_Environment::class, Twig_Environment::class)
            ->setArguments([
                $twig_loader,
                [
//                  'debug' => true,
                    'cache' => $this->getCacheDir(),
                    'auto_reload' => true
                ]
            ])
            ->addMethodCall('addExtension', [$staticPath]);

        //DB
        $this->container->register(DB::class, DB::class)
            ->setArguments([
                'mysql',
                'app',
                'app',
                'app'
            ]);

        //user info repository
        $this->container->register(UserInfoRepository::class, UserInfoRepository::class)
            ->setArguments([new Reference(DB::class)]);

        //user repository
        $this->container->register(UserRepository::class, UserRepository::class)
            ->setArguments([
                new Reference(DB::class),
                new Reference(UserInfoRepository::class)
            ]);

        //auth service
        $this->container->register(AuthenticationService::class, AuthenticationService::class)
            ->setArguments([
                new Reference(UserRepository::class),
                new Reference(UserInfoRepository::class),
                $this->getKey()
            ]);


        //LoginController
        $this->container->register(LoginController::class, LoginController::class)
            ->setArguments([
                $request,
                new Reference(Twig_Environment::class),
                new Reference(AuthenticationService::class)
                ]);

        //ProfileController
        $this->container->register(ProfileController::class, ProfileController::class)
            ->setArguments([
                $request,
                new Reference(Twig_Environment::class),
                new Reference(AuthenticationService::class),
                new Reference(UserRepository::class),
                new Reference(UserInfoRepository::class)
            ]);

        //UserInfoApiController
        $this->container->register(UserInfoApiController::class, UserInfoApiController::class)
            ->setArguments([
                $request,
                new Reference(UserRepository::class),
                new Reference(AuthenticationService::class)
            ]);

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
     * Get encrypt key for user auth cookie
     * @return string
     */
    public function getKey(): string
    {
        return 'iopdasojijioajscx,mzmc,z.xmiwqje';
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