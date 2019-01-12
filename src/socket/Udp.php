<?php

namespace Vados\TCPLogger\socket;

/**
 * Class Udp
 * @package Vados\TCPLogger\socket
 */
class Udp extends Socket
{
    /**
     * @return void
     */
    public function initialize()
    {
        $this->socket = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);
    }
}