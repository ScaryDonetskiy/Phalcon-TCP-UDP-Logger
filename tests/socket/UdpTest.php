<?php

namespace Vados\TCPLogger\Tests\socket;

use PHPUnit\Framework\TestCase;
use Vados\TCPLogger\socket\Udp;

/**
 * Class UdpTest
 * @package Vados\TCPLogger\Tests\socket
 */
class UdpTest extends TestCase
{
    /**
     * @var Udp
     */
    private $instance;

    public function setUp()
    {
        $this->instance = new Udp('localhost', 10000);
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