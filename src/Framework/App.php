<?php

namespace DrMVC\Framework;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

use Zend\Diactoros\ServerRequestFactory;
use Zend\Diactoros\Response;

use DrMVC\Controllers\Error;
use DrMVC\Router\Router;
use DrMVC\Router\RouteInterface;
use DrMVC\Router\MethodsInterface;
use DrMVC\Config\ConfigInterface;

/**
 * Main class of DrMVC application
 * @package DrMVC\Framework
 * @since   3.0
 *
 * @method App options(string $pattern, callable $callable): App
 * @method App get(string $pattern, callable $callable): App
 * @method App head(string $pattern, callable $callable): App
 * @method App post(string $pattern, callable $callable): App
 * @method App put(string $pattern, callable $callable): App
 * @method App delete(string $pattern, callable $callable): App
 * @method App trace(string $pattern, callable $callable): App
 * @method App connect(string $pattern, callable $callable): App
 */
class App implements AppInterface
{

    /**
     * Default action if method is not set
     */
    const DEFAULT_ACTION = 'index';

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
        $router->error(Error::class);

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
     * @return  MethodsInterface
     */
    public function __call(string $method, array $args): MethodsInterface
    {
        if (\in_array($method, Router::METHODS, false)) {
            $this->container('router')->set([$method], $args);
        }
        return $this;
    }

    /**
     * If any route is provided
     *
     * @param   string $pattern
     * @param   callable|string $callable
     * @return  MethodsInterface
     */
    public function any(string $pattern, $callable): MethodsInterface
    {
        $this->container('router')->any($pattern, $callable);
        return $this;
    }

    /**
     * Set the error callback of class
     *
     * @param   callable|string $callable
     * @return  MethodsInterface
     */
    public function error($callable): MethodsInterface
    {
        $this->container('router')->error($callable);
        return $this;
    }

    /**
     * Few methods provided
     *
     * @param   array $methods
     * @param   string $pattern
     * @param   callable|string $callable
     * @return  MethodsInterface
     */
    public function map(array $methods, string $pattern, $callable): MethodsInterface
    {
        $this->container('router')->map($methods, $pattern, $callable);
        return $this;
    }

    /**
     * Here we need parse line of class and extract action name after last ":" symbol
     *
     * @param   string $className
     * @return  string|null
     */
    private function extractActionFromClass(string $className)
    {
        // If class contain method name
        if (strpos($className, ':') !== false) {
            $classArray = explode(':', $className);
            $classAction = end($classArray);
        } else {
            $classAction = null;
        }
        return $classAction;
    }

    /**
     * Parse line of class, end return only class name (without possible action)
     *
     * @param   string $className
     * @return  string
     */
    private function extractClass(string $className): string
    {
        // If class name contain ":" symbol
        if (strpos($className, ':') !== false) {
            $classArray = explode(':', $className);
            $className = $classArray[0];
        }
        return $className;
    }

    /**
     * Detect action by string name, variable or use default
     *
     * @param   string $className - eg. MyApp\Index:test
     * @param   array $variables
     * @return  string
     */
    private function detectAction(string $className, array $variables = []): string
    {
        $action =
            // 1. Action name in line with class name eg. MyApp\Index:test - alias for `action_test`
            $this->extractActionFromClass($className)
            ?? (
                // 2. If action name in variables (we need first item)
                $variables['action'][0]
                // 3. Default action is index
                ?? self::DEFAULT_ACTION
            );

        return 'action_' . $action;
    }

    /**
     * Check if method exist in required class
     *
     * @param   object $class
     * @param   string $action
     * @return  bool
     */
    private function methodCheck($class, string $action): bool
    {
        try {
            // If method not found in required class
            if (!\method_exists($class, $action)) {
                $className = \get_class($class);
                throw new Exception("Method \"$action\" is not found in \"$className\"");
            }
        } catch (Exception $e) {
            return false;
        }
        return true;
    }

    /**
     * Here we need to solve how to display the page, and if method is
     * not available need to show error
     *
     * @param   RouteInterface $route
     * @param   RequestInterface $request
     * @param   ResponseInterface $response
     * @param   bool $error
     * @return  StreamInterface
     */
    private function exec(
        RouteInterface $route,
        RequestInterface $request,
        ResponseInterface $response,
        bool $error = false
    ) {
        $variables = $route->getVariables();
        $callback = $route->getCallback();

        // If extracted call back is string
        if (\is_string($callback)) {

            $className = $this->extractClass($callback);

            // Then class provided
            $class = new $className();
            $action = $this->detectAction($callback, $variables);

            // If method is not found in class and error is not triggered, then trigger error
            if (true !== $error && false === $this->methodCheck($class, $action)) {
                $router = $this->container('router');
                $routeError = $router->getError();
                return $this->exec($routeError, $request, $response, true);
            }

            // Call required action, with request/response
            $class->$action($request, $response, $variables);
        } else {
            // Else simple callback
            $callback($request, $response, $variables);
        }
        return $response->getBody();
    }

    /**
     * Simple runner should parse query and make work on user's class
     *
     * @return  StreamInterface
     */
    public function run(): StreamInterface
    {
        // Extract some important objects
        $router = $this->container('router');
        $request = $this->container('request');
        $response = $this->container('response');

        // Get current matched route with and extract variables with callback
        $route = $router->getRoute();

        return $this->exec($route, $request, $response);
    }

}
