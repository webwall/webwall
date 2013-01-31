<?php

namespace Webwall\Controllers;

use Truss\RequestHandler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiController extends RequestHandler {

	public function get($method) {

		return new Response('ApiHandler::get('.$method.')');
	}
}
	