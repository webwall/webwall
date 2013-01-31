<?php

namespace Truss\Tests;

use Truss\Application;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
 
class ApplicationTest extends \PHPUnit_Framework_TestCase {

	public function testNotFound() {
        $app = $this->getApplication(new ResourceNotFoundException());
        $response = $app->dispatch(new Request());
        $this->assertEquals(404, $response->getStatusCode());
	}

	public function testError() {
	    $app = $this->getApplication(new \RuntimeException()); 
	    $response = $app->dispatch(new Request());
	    $this->assertEquals(500, $response->getStatusCode());
	}

	protected function getApplication($exception) {
		$matcher = $this->getMock('Symfony\Component\Routing\Matcher\UrlMatcherInterface');
        $matcher
            ->expects($this->once())
            ->method('match')
            ->will($this->throwException($exception))
        ;
        $resolver = $this->getMock('Symfony\Component\HttpKernel\Controller\ControllerResolverInterface');
 
        return new Application($matcher, $resolver);	
	}
}