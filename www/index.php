<?php
//
require_once './init.php';
//
//use Symfony\Component\Config\FileLocator;
//use Symfony\Component\Routing\Loader\YamlFileLoader;
//use Symfony\Component\Routing\Matcher\UrlMatcher;
//use Symfony\Component\Routing\RequestContext;
//use Symfony\Component\Routing\Router;
//use Symfony\Component\HttpFoundation\Request;
//
//$request = $request = Request::createFromGlobals();
//
//
//$fileLocator = new FileLocator(array(__DIR__.'../src/App/config'));
//$loader = new YamlFileLoader($fileLocator);
//$routes = $loader->load('routes.yaml');
//
//$requestContext = new RequestContext('/');
//
//$router = new Router(
//    $loader,
//    array('cache_dir' => __DIR__.'/cache'),
//    $requestContext
//);
//
//
//$context = new RequestContext();
//$context->fromRequest($request);
//
//$matcher = new UrlMatcher($router->getRouteCollection(), $context);
//
//$params = $matcher->match($request->getPathInfo());
//call_user_func_array(array($params['controller'], $params['action']), array($params['username']));

use App\Authentication\RequestHandler;

$URI = $_SERVER['REQUEST_URI'];

$URL = explode('?', $URI)[0];

switch ($URL) {
    case '/signIn':
        echo requestHandler::handle($_GET['name']);
        break;
    default:
        header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found", true, 404);
        echo 404;
        break;
}

