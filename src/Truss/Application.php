<?php

namespace Truss;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Matcher\UrlMatcherInterface;
use Symfony\Component\HttpKernel\Controller\ControllerResolverInterface;

class Application {

	protected $matcher;
	protected $resolver;

	public function __construct(UrlMatcherInterface $matcher, ControllerResolverInterface $resolver) {
		$this->matcher = $matcher;
		$this->resolver = $resolver;
	}

	function attach($routes=null, $plugins=null, $config=false) {

	}

	function dispatch(Request $request) {
		try {
			$request->attributes->add($this->matcher->match($request->getPathInfo()));
			$controller = $this->resolver->getController($request);
			$arguments = $this->resolver->getArguments($request, $controller);
			return call_user_func_array($controller, $arguments);

			// $attributes = $matcher->match($request->getPathInfo());
			// $response = new Response('test');
		} catch (Routing\Exception\ResourceNotFoundException $e) {
		    return new Response('Not Found', 404);
		} catch (Exception $e) {
		    return new Response('An error occurred', 500);
		}
 
	}

	function test_method() {

	}
}