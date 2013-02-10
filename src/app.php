<?php

namespace Webwall;
// use Symfony\Component\HttpKernel\;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\HttpKernel;
use Symfony\Component\HttpKernel\EventListener\ExceptionListener;
use Symfony\Component\HttpKernel\HttpCache\HttpCache;
use Symfony\Component\HttpKernel\HttpCache\Store;

use Symfony\Component\EventDispatcher\EventDispatcher;

use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RequestContext;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

use Truss;
use Truss\Application;
use Truss\RequestHandler;
use Truss\ResponseEvent;
use Truss\ControllerResolver;

$logger = new Logger('webwall');
$log_handler = new StreamHandler(WEBWALL_ROOT . '/var/log/app_log', Logger::DEBUG);
$logger->pushHandler($log_handler);
$logger->addDebug('Started');

$request = Request::createFromGlobals();

$routes = new RouteCollection();
// $routes->add('api', new Route('/api/{method}', array('method' => 'Index', '_controller' => 'Webwall\Controllers\ApiController::get')));
// $routes->add('api', new Route('/test/{method}', array('method' => 'Index', '_controller' => 'Webwall\Controllers\ApiController::test')));
// $routes->add('api', new Route('/post/{id}', array('method' => 'Index', '_controller' => 'Webwall\Controllers\PostController::get')));
$routes->add('posts', new Route('/posts/{stub}', array('_controller' => 'Webwall\Controllers\TestController::_method')));

$routes->add('test', new Route('/test', array('_controller' => 'Webwall\Controllers\IndexController::get'), array('_method' => 'GET')));

$routes->add('d0', new Route('/{_object}/{_method}/{_params}', array('_object' => 'index', '_method' => 'get', '_params' => ''), array('_params' => '.*', '_method' => 'get')));
// $routes->add('d1', new Route('/{_object}/{_method}'));
// $routes->add('d2', new Route('/{_object}'));
// $routes->add('d3', new Route('/'));



$context = new RequestContext();
$context->fromRequest($request);

$matcher = new UrlMatcher($routes, $context);

$resolver = new Truss\DynamicControllerResolver("Webwall\Controllers");


$dispatcher = new EventDispatcher();
$dispatcher->addSubscriber(new ExceptionListener('Webwall\\Controllers\\ErrorController::exceptionAction'));
$dispatcher->addSubscriber(new Truss\Listeners\ContentLengthListener());
$dispatcher->addSubscriber(new Truss\Listeners\StringResponseListener());
$dispatcher->addSubscriber(new Truss\Listeners\ArrayResponseListener());
// $dispatcher->addSubscriber(new Truss\Listeners\DefaultRouteListener());
$dispatcher->addSubscriber(new HttpKernel\EventListener\RouterListener($matcher));



class GenericApp extends HttpKernel\HttpKernel {

}



$cache_dir = WEBWALL_WEBROOT . '/../var/cache';
if(!is_dir($cache_dir)) {
	throw new Exception('bad cache dir ' . $cache_dir);
}
$store = new Store($cache_dir);

$app = new Application($dispatcher, $resolver);
// $app = new GenericApp($dispatcher, $resolver);
// $app = new HttpCache($app, $store);
// $app->handle($request)->send();

$app->run();

// $app->handle($request)->send();

// $app2 = new GenericApp($dispatcher, $resolver);
// $app2 = new HttpCache($app2, $store);
// $app2->handle($request)->send();


// $app = new Application($dispatcher, $resolver, $matcher);
// $app = new HttpCache($app, $store);
// $app->handle($request)->send();

// $response = $app->dispatch($request);

// $kernel = new HttpKernel($dispatcher, $resolver);

// $kernel->handle($request)->send();
// return 1;


// $response->send();