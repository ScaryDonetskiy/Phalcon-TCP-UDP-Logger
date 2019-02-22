<?php

namespace Vados\TCPLogger\Tests\socket;

use PHPUnit\Framework\TestCase;
use Vados\TCPLogger\socket\Tcp;

/**
 * Class TcpTest
 * @package Vados\TCPLogger\Tests\socket
 */
class TcpTest extends TestCase
{
    /**
     * @var Tcp
     */
    private $instance;

    public function setUp()
    {
        $this->instance = new Tcp('localhost', 10000);
    }

    /**
     * @throws \Exception
     */
    public function testSocketInitialize()
    {
        $socketInitialize = new \ReflectionMethod($this->instance, 'initialize');
        $socketInitialize->setAccessible(true);
        $socketInitialize->invoke($this->instance);
        $socket = new \ReflectionProperty($this->instance, 'socket');
        $socket->setAccessible(true);
        $this->assertIsResource($socket->getValue($this->instance));
    }
}