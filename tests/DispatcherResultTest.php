<?php


use Racoon\Router\DispatcherResult;
use Racoon\Router\Exception\NotFoundException;
use Racoon\Router\RouteCollector;
use Racoon\Router\Router;

class DispatcherResultTestHandler
{
    public function doSomething()
    {
        return true;
    }

    public function getWildcards()
    {
        return true;
    }
}

class DispatcherResultTest extends PHPUnit_Framework_TestCase
{

    protected function checkRoute(Router $router, $httpMethod, $uri, $handler = 'DispatcherResultTestHandler', $method = 'doSomething', & $result = null)
    {
        try {
            $result = $router->processRoutes($httpMethod, $uri)->getDispatcherResult();
            if (is_object($result) && $result->getClass() == $handler && $result->getMethod() == $method) {
                $valid = true;
            } else {
                $valid = false;
            }
        } catch (NotFoundException $e) {
            $valid = false;
        }

        return $valid;
    }

    public function testHttpMethodWorks()
    {
        $router = new Router();
        
        $router->addRouteCallable(function(RouteCollector $r) {
            $r->addRoute('GET', '/tester/asd', 'DispatcherResultTestHandler@doSomething', true);
        });

        $router->init();

        $this->assertTrue($this->checkRoute($router, 'GET', '/tester/asd'));
        $this->assertFalse($this->checkRoute($router, 'POST', '/tester/asd'));
        $this->assertFalse($this->checkRoute($router, 'PUT', '/tester/asd'));
        $this->assertFalse($this->checkRoute($router, 'DELETE', '/tester/asd'));
    }

    public function testUriWorks()
    {
        $router = new Router();

        $router->addRouteCallable(function(RouteCollector $r) {
            $r->addRoute('GET', '/tester/do-something', 'DispatcherResultTestHandler@doSomething', true);
        });

        $router->init();

        $this->assertTrue($this->checkRoute($router, 'GET', '/tester/do-something'));
        $this->assertFalse($this->checkRoute($router, 'GET', '/tester/do-something-else'));
        $this->assertFalse($this->checkRoute($router, 'GET', '/tester/do-somethings'));
    }

    public function testWildcards()
    {
        $router = new Router();

        $router->addRouteCallable(function(RouteCollector $r) {
            $r->addRoute('GET', '/tester/do-something', 'DispatcherResultTestHandler@getWildcards', true);
            $r->addRoute('GET', '/tester/do-something/{wc1}/{wc2}/{wc3}', 'DispatcherResultTestHandler@getWildcards', true);
            $r->addRoute('GET', '/tester/do-something/{wc1}', 'DispatcherResultTestHandler@getWildcards', true);
        });

        $router->init();

        /**
         * @var DispatcherResult $dispatcherResult
         */
        $dispatcherResult = null;

        $this->assertTrue($this->checkRoute($router, 'GET', '/tester/do-something', 'DispatcherResultTestHandler', 'getWildcards', $dispatcherResult));
        if (is_object($dispatcherResult)) {
            $this->assertEquals($dispatcherResult->getVars(), []);
        }

        $this->assertFalse($this->checkRoute($router, 'GET', '/tester/do-something/asd/qwe', 'DispatcherResultTestHandler', 'getWildcards', $dispatcherResult));

        $this->assertTrue($this->checkRoute($router, 'GET', '/tester/do-something/asd/qwe/zxc', 'DispatcherResultTestHandler', 'getWildcards', $dispatcherResult));
        if (is_object($dispatcherResult)) {
            $this->assertEquals($dispatcherResult->getVars(), ['wc1' => 'asd', 'wc2' => 'qwe', 'wc3' => 'zxc']);
        }

        $this->assertTrue($this->checkRoute($router, 'GET', '/tester/do-something/something', 'DispatcherResultTestHandler', 'getWildcards', $dispatcherResult));
        if (is_object($dispatcherResult)) {
            $this->assertEquals($dispatcherResult->getVars(), ['wc1' => 'something']);
        }
    }

    public function testGroupWorks()
    {
        $router = new Router();

        $router->addRouteCallable(function(RouteCollector $r) {
            $r->addGroup('/tester', function (RouteCollector $r) {
                $r->addRoute('GET', '/do-something', 'DispatcherResultTestHandler@doSomething', true);
                $r->addGroup('/more', function (RouteCollector $r) {
                    $r->addRoute('GET', '/things', 'DispatcherResultTestHandler@doSomething', true);
                });
            });
        });

        $router->init();

        $this->assertTrue($this->checkRoute($router, 'GET', '/tester/do-something'));
        $this->assertFalse($this->checkRoute($router, 'GET', '/tester/do-something-else'));
        $this->assertFalse($this->checkRoute($router, 'GET', '/tester/do-somethings'));
        $this->assertTrue($this->checkRoute($router, 'GET', '/tester/more/things'));
    }

}