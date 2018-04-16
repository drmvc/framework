<?php

namespace DrMVC\Framework\Tests;

use PHPUnit\Framework\TestCase;
use Zend\Diactoros\ServerRequest;
use Zend\Diactoros\Response;
use Zend\Diactoros\Stream;
use DrMVC\Framework\App;
use DrMVC\Framework\Containers;
use DrMVC\Config\Config;
use DrMVC\Router\Router;
use DrMVC\Router\Route;

class AppTest extends TestCase
{
    private $config;

    public function __construct(string $name = null, array $data = [], string $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->config = new Config();
    }

    public function test__construct()
    {
        try {
            $obj = new App($this->config);
            $this->assertInternalType('object', $obj);
            $this->assertInstanceOf(App::class, $obj);
        } catch (\Exception $e) {
            $this->assertContains('Must be initialized ', $e->getMessage());
        }
    }

    public function testContainers()
    {
        $obj = new App($this->config);

        $containers = $obj->containers();
        $this->assertInternalType('object', $containers);
        $this->assertInstanceOf(Containers::class, $containers);

        $config = $containers->get('config');
        $this->assertInternalType('object', $config);
        $this->assertInstanceOf(Config::class, $config);
    }

    public function testContainer()
    {
        $obj = new App($this->config);

        $empty = $obj->container('empty');
        $this->assertEmpty($empty);

        $config = $obj->container('config');
        $this->assertInternalType('object', $config);
        $this->assertInstanceOf(Config::class, $config);

        $request = $obj->container('request');
        $this->assertInternalType('object', $request);
        $this->assertInstanceOf(ServerRequest::class, $request);

        $response = $obj->container('response');
        $this->assertInternalType('object', $response);
        $this->assertInstanceOf(Response::class, $response);

        $router = $obj->container('router');
        $this->assertInternalType('object', $router);
        $this->assertInstanceOf(Router::class, $router);
    }

    public function test__call()
    {
        $obj = new App($this->config);
        $obj
            ->options('/options', function() {
                return "options\n";
            })
            ->get('/get', function() {
                return "get\n";
            })
            ->head('/head', function() {
                return "head\n";
            })
            ->post('/post', function() {
                return "post\n";
            })
            ->put('/put', function() {
                return "put\n";
            })
            ->delete('/delete', function() {
                return "delete\n";
            })
            ->trace('/trace', function() {
                return "trace\n";
            })
            ->connect('/connect', function() {
                return "connect\n";
            });

        $routes = $obj->container('router')->getRoutes();

        $this->assertInternalType('array', $routes);
        $this->assertCount(8, $routes);
    }


    public function testError()
    {
        $obj = new App($this->config);
        $obj->error(function() {
            return "error\n";
        });
        $error = $obj->container('router')->getError();

        $this->assertInternalType('object', $error);
        $this->assertInstanceOf(Route::class, $error);
    }

    public function testAny()
    {
        $obj = new App($this->config);
        $obj
            ->any('/any', function() {
                return "any\n";
            });

        $routes = $obj->container('router')->getRoutes();

        $this->assertInternalType('array', $routes);
        $this->assertCount(1, $routes);
    }

    public function testMap()
    {
        $obj = new App($this->config);
        $obj
            ->map(['get', 'post'], '/map', function() {
                return "map\n";
            });

        $routes = $obj->container('router')->getRoutes();

        $this->assertInternalType('array', $routes);
        $this->assertCount(1, $routes);
    }

    public function testRun()
    {
        $obj = new App($this->config);
        $obj
            ->map(['get', 'post'], '/map', function() {
                return "map\n";
            });
        $stream = $obj->run();

        $this->assertInternalType('object', $stream);
        $this->assertInstanceOf(Stream::class, $stream);
    }
}
