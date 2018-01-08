<?php

namespace ScaryDonetskiy\TCPLogger;

use Phalcon\Logger as PhalconLogger;
use Phalcon\Logger\AdapterInterface;
use Phalcon\Logger\Formatter\Line;
use Phalcon\Logger\FormatterInterface;

/**
 * Class Logger
 * @package Dockent\components
 */
class Adapter implements AdapterInterface
{
    /**
     * @var FormatterInterface
     */
    private $formatter;

    /**
     * @var int
     */
    private $logLevel = PhalconLogger::ERROR;

    /**
     * @var bool
     */
    private $transactionStatus = false;

    /**
     * @var array
     */
    private $transactionStack = [];

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
    private $socket = null;

    /**
     * Logger constructor.
     * @param string $host
     * @param int $port
     */
    public function __construct(string $host, int $port)
    {
        $this->host = $host;
        $this->port = $port;
    }

    /**
     * @return bool
     */
    private function socketInitialize(): bool
    {
        $this->socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        return @socket_connect($this->socket, $this->host, $this->port);
    }

    /**
     * Sets the message formatter
     *
     * @param FormatterInterface $formatter
     * @return AdapterInterface
     */
    public function setFormatter(FormatterInterface $formatter): AdapterInterface
    {
        $this->formatter = $formatter;
        return $this;
    }

    /**
     * Returns the internal formatter
     *
     * @return FormatterInterface
     */
    public function getFormatter(): FormatterInterface
    {
        if ($this->formatter === null) {
            $this->formatter = new Line();
        }
        return $this->formatter;
    }

    /**
     * Filters the logs sent to the handlers to be greater or equals than a specific level
     *
     * @param int $level
     * @return AdapterInterface
     */
    public function setLogLevel($level): AdapterInterface
    {
        $this->logLevel = $level;
        return $this;
    }

    /**
     * Returns the current log level
     *
     * @return int
     */
    public function getLogLevel(): int
    {
        return $this->logLevel;
    }

    /**
     * Sends/Writes messages to the file log
     *
     * @param mixed $type
     * @param mixed $message
     * @param array $context
     * @return AdapterInterface
     */
    public function log($type, $message = null, array $context = null): AdapterInterface
    {
        if ($this->transactionStatus) {
            $this->transactionStack[] = [
                'type' => $type,
                'message' => $message,
                'context' => $context
            ];
        } else {
            if ($this->socket === null) {
                if (!$this->socketInitialize()) {
                    return $this;
                }
            }
            $package = $this->getFormatter()->format($message, $type, time(), $context);
            $package = str_replace(PHP_EOL, '<=>', $package) . PHP_EOL;
            socket_send($this->socket, $package, strlen($package), 0);
        }
        return $this;
    }

    /**
     * Starts a transaction
     *
     * @return AdapterInterface
     */
    public function begin(): AdapterInterface
    {
        $this->transactionStatus = true;
        return $this;
    }

    /**
     * Commits the internal transaction
     *
     * @return AdapterInterface
     */
    public function commit(): AdapterInterface
    {
        $this->transactionStatus = false;
        foreach ($this->transactionStack as $item) {
            $this->log($item['type'], $item['message'], $item['context']);
        }
        $this->transactionStack = [];
        return $this;
    }

    /**
     * Rollbacks the internal transaction
     *
     * @return AdapterInterface
     */
    public function rollback(): AdapterInterface
    {
        $this->transactionStack = [];
        $this->transactionStatus = false;
        return $this;
    }

    /**
     * Closes the logger
     *
     * @return bool
     */
    public function close(): bool
    {
        if ($this->socket !== null) {
            @socket_close($this->socket);
        }
        return true;
    }

    /**
     * Sends/Writes a debug message to the log
     *
     * @param string $message
     * @param array $context
     * @return AdapterInterface
     */
    public function debug($message, array $context = null): AdapterInterface
    {
        $this->log(PhalconLogger::DEBUG, $message, $context);
        return $this;
    }

    /**
     * Sends/Writes an error message to the log
     *
     * @param string $message
     * @param array $context
     * @return AdapterInterface
     */
    public function error($message, array $context = null): AdapterInterface
    {
        $this->log(PhalconLogger::ERROR, $message, $context);
        return $this;
    }

    /**
     * Sends/Writes an info message to the log
     *
     * @param string $message
     * @param array $context
     * @return AdapterInterface
     */
    public function info($message, array $context = null): AdapterInterface
    {
        $this->log(PhalconLogger::INFO, $message, $context);
        return $this;
    }

    /**
     * Sends/Writes a notice message to the log
     *
     * @param string $message
     * @param array $context
     * @return AdapterInterface
     */
    public function notice($message, array $context = null): AdapterInterface
    {
        $this->log(PhalconLogger::NOTICE, $message, $context);
        return $this;
    }

    /**
     * Sends/Writes a warning message to the log
     *
     * @param string $message
     * @param array $context
     * @return AdapterInterface
     */
    public function warning($message, array $context = null): AdapterInterface
    {
        $this->log(PhalconLogger::WARNING, $message, $context);
        return $this;
    }

    /**
     * Sends/Writes an alert message to the log
     *
     * @param string $message
     * @param array $context
     * @return AdapterInterface
     */
    public function alert($message, array $context = null): AdapterInterface
    {
        $this->log(PhalconLogger::ALERT, $message, $context);
        return $this;
    }

    /**
     * Sends/Writes an emergency message to the log
     *
     * @param string $message
     * @param array $context
     * @return AdapterInterface
     */
    public function emergency($message, array $context = null): AdapterInterface
    {
        $this->log(PhalconLogger::EMERGENCY, $message, $context);
        return $this;
    }

    public function __destruct()
    {
        $this->close();
    }
}