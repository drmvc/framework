<?php

namespace DrMVC\Framework;

use DrMVC\Router\MethodsInterface;
use Zend\Diactoros\ServerRequestFactory;
use Zend\Diactoros\ServerRequest;
use Zend\Diactoros\Response;

use DrMVC\Config\ConfigInterface;
use DrMVC\Router\Router;
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
        //$router->setError(Error::class);

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
     * Detect action by string name, variable or use default
     *
     * @param   string $className - eg. MyApp\Index:test
     * @param   array $variables
     * @return  string
     */
    private function detectAction(string $className, array $variables = []): string
    {
        // If class contain method name
        if (strpos($className, ':') !== false) {
            $classArray = explode(':', $className);
            $classAction = end($classArray);
        } else {
            $classAction = null;
        }

        /*
         * Detect what action should be initiated
         *
         * Priorities:
         *  1. If action name in variables
         *  2. Action name in line with class name eg. MyApp\Index:test - test here is `action_test` alias
         *  3. Default action is index - action_index in result
         */
        $action = self::DEFAULT_ACTION;
        if (null !== $classAction) {
            $action = $classAction;
        }
        if (isset($variables['action'][0])) {
            $action = $variables['action'][0];
        }

        return 'action_' . $action;
    }

    /**
     * Simple runner should parse query and make work on user's class
     *
     * @return  mixed
     */
    public function run()
    {
        // Extract some important objects
        $router = $this->container('router');
        $request = $this->container('request');
        $response = $this->container('response');

        // Get current matched route with and extract variables with callback
        $route = $router->getRoute();
        $variables = $route->getVariables();
        $callback = $route->getCallback();

        // If extracted call back is string
        if (\is_string($callback)) {

            // Then class provided
            $class = new $callback();
            $action = $this->detectAction($callback, $variables);

            // TODO: crapcode, rewrite to exceptions
            // If method not found in required class
            if (!\method_exists($class, $action)) {
                // Replace route object to error route
                $route = $router->getError();
                $variables = $route->getVariables();
                $callback = $route->getCallback();

                // If extracted call back is string
                if (\is_string($callback)) {
                    // Then class provided
                    $class = new $callback();
                    $action = $this->detectAction($callback);

                    // Call required action, with request/response
                    $class->$action($request, $response, $variables);
                } else {
                    // Else simple callback
                    $callback($request, $response, $variables);
                }
                return $response->getBody();
            }

            // Call required action, with request/response
            $class->$action($request, $response, $variables);
        } else {
            // Else simple callback
            $callback($request, $response, $variables);
        }

        return $response->getBody();
    }

}
