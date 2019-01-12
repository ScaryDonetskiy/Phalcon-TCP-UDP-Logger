<?php

namespace Vados\TCPLogger\socket;

/**
 * Class Tcp
 * @package Vados\TCPLogger\socket
 */
class Tcp extends Socket
{
    /**
     * @return void
     */
    public function initialize()
    {
        $this->socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
    }
}