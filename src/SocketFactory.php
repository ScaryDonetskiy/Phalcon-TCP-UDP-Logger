<?php

namespace Vados\TCPLogger;

use Vados\TCPLogger\socket\Socket;
use Vados\TCPLogger\socket\Tcp;
use Vados\TCPLogger\socket\Udp;

/**
 * Class SocketFactory
 * @package Vados\TCPLogger
 */
class SocketFactory
{
    /**
     * @param int $protocol
     * @param string $host
     * @param int $port
     * @return Socket
     */
    public static function getByProtocol(int $protocol, string $host, int $port): Socket
    {
        switch ($protocol) {
            case Protocol::TCP:
                return new Tcp($host, $port);
            case Protocol::UDP:
            default:
                return new Udp($host, $port);
        }
    }
}