<?php namespace DrMVC;

use PHPUnit\Framework\TestCase;

class ConfigTest extends TestCase
{
    /**
     * @test __construct
     */
    public function testInit()
    {
        $array = ['param' => 'value'];
        $config = new Config($array);
        $this->assertTrue((isset ($config->param)));
    }
}
