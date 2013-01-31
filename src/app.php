<?php

namespace Webwall;
// use Symfony\Component\HttpKernel\;

use Symfony\Component\HttpKernel;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\HttpKernel\Controller\ControllerResolver;


use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RequestContext;

use Truss\Application;
use Truss\RequestHandler;


$request = Request::createFromGlobals();
$routes = new RouteCollection();
 
// $routes->add('hello', new Route('/hello/{name}', array('name' => 'World')));
// $routes->add('bye', new Route('/bye'));
$routes->add('api', new Route('/api/{method}', array('method' => 'Index', '_controller' => 'Webwall\Controllers\ApiController::get')));



$context = new RequestContext();
$context->fromRequest($request);
$matcher = new UrlMatcher($routes, $context);

// $dispatcher = new EventDispatcher();
// $dispatcher->addSubscriber(new RouteListener($matcher));

$resolver = new ControllerResolver();

$app = new Application($matcher, $resolver);
$response = $app->dispatch($request);

// $kernel = new HttpKernel($dispatcher, $resolver);

// $kernel->handle($request)->send();
// return 1;


$response->send();