<?php

namespace DrMVC\Framework;

use DrMVC\Router\RouterInterface;
use Zend\Diactoros\ServerRequestFactory;
use Zend\Diactoros\ServerRequest;
use Zend\Diactoros\Response;

use DrMVC\Config\ConfigInterface;
use DrMVC\Router;
use DrMVC\Controllers\Error;

/**
 * Class App
 * @package DrMVC\Framework
 * @method App options(string $pattern, callable $callable): App
 * @method App get(string $pattern, callable $callable): App
 * @method App head(string $pattern, callable $callable): App
 * @method App post(string $pattern, callable $callable): App
 * @method App put(string $pattern, callable $callable): App
 * @method App delete(string $pattern, callable $callable): App
 * @method App trace(string $pattern, callable $callable): App
 * @method App connect(string $pattern, callable $callable): App
 * @since 3.0
 */
class App implements AppInterface
{
    /**
     * @var ContainersInterface
     */
    private $_containers;

    /**
     * App constructor.
     * @param ConfigInterface $config
     */
    public function __construct(ConfigInterface $config)
    {
        // Initiate PSR-11 containers
        $this->initContainers();

        // Save configuration
        $this->initConfig($config);

        // Initiate router
        $this
            ->initRequest()
            ->initResponse()
            ->initRouter();
    }

    /**
     * Initialize containers object
     *
     * @return  App
     */
    private function initContainers(): App
    {
        if (null === $this->_containers) {
            $this->_containers = new Containers();
        }
        return $this;
    }

    /**
     * Put config object into the containers class
     *
     * @param   ConfigInterface $config
     * @return  App
     */
    private function initConfig(ConfigInterface $config): App
    {
        $this->containers()->set('config', $config);
        return $this;
    }

    /**
     * Initiate PSR-7 request object
     *
     * @return  App
     */
    private function initRequest(): App
    {
        try {
            $request = ServerRequestFactory::fromGlobals();
            $this->containers()->set('request', $request);
        } catch (\InvalidArgumentException $e) {
            new Exception($e);
        }
        return $this;
    }

    /**
     * Initiate PSR-7 response object
     *
     * @return  App
     */
    private function initResponse(): App
    {
        try {
            $response = new Response();
            $this->containers()->set('response', $response);
        } catch (\InvalidArgumentException $e) {
            new Exception($e);
        }
        return $this;
    }

    /**
     * Put route into the container of classes
     *
     * @return  App
     */
    private function initRouter(): App
    {
        $request = $this->container('request');
        $response = $this->container('response');
        $router = new Router($request, $response);
        $router->setError(Error::class);

        $this->containers()->set('router', $router);
        return $this;
    }

    /**
     * Get custom container by name
     *
     * @param   string $name
     * @return  mixed
     */
    public function container(string $name)
    {
        return $this->_containers->get($name);
    }

    /**
     * Get all available containers
     *
     * @return  ContainersInterface
     */
    public function containers(): ContainersInterface
    {
        return $this->_containers;
    }

    /**
     * Magic method for work with calls
     *
     * @param   string $method
     * @param   array $args
     * @return  RouterInterface
     */
    public function __call(string $method, array $args): RouterInterface
    {
        $router = $this->container('router');
        if (\in_array($method, Router::METHODS, false)) {
            $router->set([$method], $args);
        }
        return $router;
    }

    /**
     * If any route is provided
     *
     * @param   string $pattern
     * @param   callable|string $callable
     * @return  RouterInterface
     */
    public function any(string $pattern, $callable): RouterInterface
    {
        $router = $this->container('router');
        $router->any($pattern, $callable);
        return $router;
    }

    /**
     * Set the error callback of class
     *
     * @param   callable|string $callable
     * @return  RouterInterface
     */
    public function error($callable): RouterInterface
    {
        $router = $this->container('router');
        $router->error($callable);
        return $router;
    }

    /**
     * Few methods provided
     *
     * @param   array $methods
     * @param   string $pattern
     * @param   callable|string $callable
     * @return  RouterInterface
     */
    public function map(array $methods, string $pattern, $callable): RouterInterface
    {
        $router = $this->container('router');
        $router->map($methods, $pattern, $callable);
        return $router;
    }

    public function run(): bool
    {
        $router = $this->container('router');
        return $router->getRoute();
    }
}
