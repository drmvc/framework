<?php namespace DrMVC;

use PHPUnit\Framework\TestCase;

class DiTest extends TestCase
{
    private $_di;
    private $_test;

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->_di = new Di();
        $this->_test[1] = new \stdClass();
        $this->_test[2] = array(1, 2, 3);
    }

    public function testSet()
    {
        $result = $this->_di->set('object', $this->_test[1]);
        $this->assertTrue($result);
        $this->assertObjectHasAttribute('object', $this->_di);

        $result = $this->_di->set('array', $this->_test[2]);
        $this->assertTrue($result);
        $this->assertObjectHasAttribute('array', $this->_di);
    }

    public function testGet()
    {
        $this->_di->set('object', $this->_test[1]);
        $result = $this->_di->get('object');
        $this->assertTrue(($result === $this->_test[1]));

        $this->_di->set('array', $this->_test[2]);
        $result = $this->_di->get('array');
        $this->assertTrue(($result === $this->_test[2]));
    }

    public function testHas()
    {
        $this->_di->set('object', $this->_test[1]);
        $result = $this->_di->has('object');
        $this->assertTrue($result);

        $result = $this->_di->has('empty');
        $this->assertFalse($result);
    }

    public function testRemove()
    {
        $this->_di->set('object', $this->_test[1]);
        $result = $this->_di->has('object');
        $this->assertTrue($result);

        $this->_di->remove('object');
        $result = $this->_di->has('object');
        $this->assertFalse($result);
    }
}
