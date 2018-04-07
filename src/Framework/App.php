<?php

namespace DrMVC\Framework;

//use Psr\Container\ContainerInterface;
//use Psr\Container\NotFoundExceptionInterface;

use DrMVC\Router\RouterInterface;
use Zend\Diactoros\ServerRequestFactory;
use Zend\Diactoros\ServerRequest;
use Zend\Diactoros\Response;

use DrMVC\Config\ConfigInterface;
use DrMVC\Router;

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
        $this
            ->initContainers()
            ->initConfig($config)
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
     * Put route into the container of classes
     *
     * @return  App
     */
    private function initRouter(): App
    {
        $req = ServerRequestFactory::fromGlobals();
        $res = new Response();
        $router = new Router($req, $res);

        $this->containers()->set('router', $router);
        return $this;
    }

    /**
     * Get containers object
     *
     * @param   string|null $name
     * @return  mixed
     */
    public function containers(string $name = null)
    {
        return (null !== $name)
            ? $this->_containers->get($name)
            : $this->_containers;
    }

    public function __call(string $method, array $args): RouterInterface
    {
        $router = $this->containers('router');
        if (\in_array($method, Router::METHODS, false)) {
            $router->set([$method], $args);
        }
        return $router;
    }

    public function any(string $pattern, $callable): RouterInterface
    {
        $router = $this->containers('router');
        $router->any($pattern, $callable);
        return $router;
    }

    public function error($callable): RouterInterface
    {
        $router = $this->containers('router');
        $router->error($callable);
        return $router;
    }

    public function map(array $methods, string $pattern, $callable): RouterInterface
    {
        $router = $this->containers('router');
        $router->map($methods, $pattern, $callable);
        return $router;
    }

}
