<?php

namespace Vados\TCPLogger\socket;

/**
 * Class Socket
 * @package Vados\TCPLogger\socket
 */
abstract class Socket
{
    /**
     * @var string
     */
    private $host;

    /**
     * @var int
     */
    private $port;

    /**
     * @var resource
     */
    protected $socket;

    /**
     * Socket constructor.
     * @param string $host
     * @param int $port
     */
    public function __construct(string $host, int $port)
    {
        $this->host = $host;
        $this->port = $port;
    }

    /**
     * @return void
     */
    abstract public function initialize();

    /**
     * @return bool
     */
    private function connect(): bool
    {
        return socket_connect($this->socket, $this->host, $this->port);
    }

    /**
     * @param string $message
     * @return int
     */
    public function send(string $message): int
    {
        if ($this->socket === null) {
            $this->initialize();
            $this->connect();
        }
        return socket_send($this->socket, $message, strlen($message), 0);
    }

    /**
     * @return bool
     */
    public function close(): bool
    {
        if ($this->socket !== null && is_resource($this->socket)) {
            socket_close($this->socket);
        }
        return true;
    }
}