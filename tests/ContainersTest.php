<?php

namespace Framework;

use PHPUnit\Framework\TestCase;
use DrMVC\Framework\Containers;
use DrMVC\Router;

class ContainersTest extends TestCase
{
    public function test__construct()
    {
        try {
            $obj = new Containers();
            $this->assertInternalType('object', $obj);
            $this->assertInstanceOf(Containers::class, $obj);
        } catch (\Exception $e) {
            $this->assertContains('Must be initialized ', $e->getMessage());
        }
    }

    public function testHas()
    {
        $obj = new Containers();
        $this->assertFalse($obj->has('router'));
        $this->assertFalse($obj->has(null));
    }

    public function testGet()
    {
        $obj = new Containers();
        $this->assertEmpty($obj->get('router'));
        $this->assertEmpty($obj->get(null));
    }

    public function testSet()
    {
        $req = new \Zend\Diactoros\ServerRequest();
        $res = new \Zend\Diactoros\Response();
        $router = new Router($req, $res);

        $obj = new Containers();
        $obj->set('router', $router);
        $this->assertInstanceOf(Containers::class, $obj);

        $has = $obj->has('router');
        $this->assertTrue($has);

        $get = $obj->get('router');
        $this->assertInstanceOf(Router::class, $get);
    }

}
