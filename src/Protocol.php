<?php

namespace Vados\TCPLogger;

/**
 * Class SocketType
 * @package Vados\TCPLogger
 */
abstract class Protocol
{
    const TCP = SOL_TCP;
    const UDP = SOL_UDP;
}