<?php

namespace Vados\TCPLogger\Tests;

use PHPUnit\Framework\TestCase;
use Vados\TCPLogger\Protocol;
use Vados\TCPLogger\socket\Tcp;
use Vados\TCPLogger\socket\Udp;
use Vados\TCPLogger\SocketFactory;

/**
 * Class SocketFactoryTest
 * @package Vados\TCPLogger\Tests
 */
class SocketFactoryTest extends TestCase
{
    public function testGetByProtocolTcp()
    {
        $instance = SocketFactory::getByProtocol(Protocol::TCP, '', 0);
        $this->assertInstanceOf(Tcp::class, $instance);
    }

    public function testGetByProtocolUdp()
    {
        $instance = SocketFactory::getByProtocol(Protocol::UDP, '', 0);
        $this->assertInstanceOf(Udp::class, $instance);
    }
}