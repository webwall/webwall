<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Matcher\UrlMatcher;
 

$request = Request::createFromGlobals();
$routes = new RouteCollection();
 
$routes->add('hello', new Route('/hello/{name}', array('name' => 'World')));
$routes->add('bye', new Route('/bye'));

$context = new RequestContext();
$context->fromRequest($request);
$matcher = new UrlMatcher($routes, $context);
 


try {
	$attributes = $matcher->match($request->getPathInfo());	
} catch (Routing\Exception\ResourceNotFoundException $e) {
    $response = new Response('Not Found', 404);
} catch (Exception $e) {
    $response = new Response('An error occurred', 500);
}
 
$response->send();