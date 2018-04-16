<?php

namespace DrMVC\Framework\Tests;

use PHPUnit\Framework\TestCase;
use DrMVC\Framework\Exception;

class ExceptionTest extends TestCase
{
    public function test__construct()
    {
        try {
            $obj = new Exception('Check call from "Exception" class');
            $this->assertInternalType('object', $obj);
            $this->assertInstanceOf(Exception::class, $obj);
        } catch (\Exception $e) {
            $this->assertContains('Must be initialized ', $e->getMessage());
        }
    }
}
