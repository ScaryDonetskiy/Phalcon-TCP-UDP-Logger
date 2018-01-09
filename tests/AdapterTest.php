<?php

namespace Vados\TCPLogger\Tests;

use Vados\TCPLogger\Adapter;
use Phalcon\Logger as PhalconLogger;
use Phalcon\Logger\AdapterInterface;
use Phalcon\Logger\Formatter\Json;
use Phalcon\Logger\FormatterInterface;
use PHPUnit\Framework\TestCase;

/**
 * Class AdapterTest
 * @package Vados\TCPLogger\Tests
 */
class AdapterTest extends TestCase
{
    const HOST = '127.0.0.1';
    const PORT = '80';

    /**
     * @var Adapter
     */
    private $instance;

    public function setUp()
    {
        $this->instance = new Adapter(self::HOST, self::PORT);
    }

    /**
     * @throws \Exception
     */
    public function testConstructor()
    {
        $this->assertInstanceOf(AdapterInterface::class, $this->instance);
    }

    /**
     * @throws \Exception
     */
    public function testSetFormatter()
    {
        $this->assertInstanceOf(AdapterInterface::class, $this->instance->setFormatter(new Json()));
        $this->assertInstanceOf(Json::class, $this->instance->getFormatter());
    }

    /**
     * @throws \Exception
     */
    public function testGetFormatter()
    {
        $this->assertInstanceOf(FormatterInterface::class, $this->instance->getFormatter());
    }

    /**
     * @throws \Exception
     */
    public function testSetLogLevel()
    {
        $this->assertInstanceOf(AdapterInterface::class, $this->instance->setLogLevel(PhalconLogger::ALERT));
        $this->assertEquals(PhalconLogger::ALERT, $this->instance->getLogLevel());
    }

    /**
     * @throws \Exception
     */
    public function testGetLogLevel()
    {
        $this->assertEquals(PhalconLogger::ERROR, $this->instance->getLogLevel());
    }

    /**
     * @throws \Exception
     */
    public function testTransaction()
    {
        /** Begin */
        $this->assertInstanceOf(AdapterInterface::class, $this->instance->begin());
        $transactionStatus = new \ReflectionProperty($this->instance, 'transactionStatus');
        $transactionStatus->setAccessible(true);
        $this->assertTrue($transactionStatus->getValue($this->instance));
        /** Log */
        $transactionStack = new \ReflectionProperty($this->instance, 'transactionStack');
        $transactionStack->setAccessible(true);
        $this->assertInternalType('array', $transactionStack->getValue($this->instance));
        $this->assertEmpty($transactionStack->getValue($this->instance));
        $this->assertInstanceOf(AdapterInterface::class, $this->instance->log(PhalconLogger::CRITICAL,
            'Message',
            ['Context' => 'Array']));
        $this->assertNotEmpty($transactionStack->getValue($this->instance));
        $this->assertEquals([
            'type' => PhalconLogger::CRITICAL,
            'message' => 'Message',
            'context' => ['Context' => 'Array']
        ], $transactionStack->getValue($this->instance)[0]);
        /** Commit */
        $this->assertInstanceOf(AdapterInterface::class, $this->instance->commit());
        $this->assertFalse($transactionStatus->getValue($this->instance));
        $this->assertEmpty($transactionStack->getValue($this->instance));
        /** Rollback */
        $this->assertInstanceOf(AdapterInterface::class, $this->instance->rollback());
        $this->assertInternalType('array', $transactionStack->getValue($this->instance));
        $this->assertEmpty($transactionStack->getValue($this->instance));
        $this->assertFalse($transactionStatus->getValue($this->instance));
    }

    /**
     * @throws \Exception
     */
    public function testRollback()
    {
        $this->instance->begin();
        $this->instance->log(PhalconLogger::CRITICAL, 'Message', ['Context' => 'Array']);
        $this->assertInstanceOf(AdapterInterface::class, $this->instance->rollback());
        $transactionStatus = new \ReflectionProperty($this->instance, 'transactionStatus');
        $transactionStatus->setAccessible(true);
        $this->assertFalse($transactionStatus->getValue($this->instance));
        $transactionStack = new \ReflectionProperty($this->instance, 'transactionStack');
        $transactionStack->setAccessible(true);
        $this->assertEmpty($transactionStack->getValue($this->instance));
    }

    /**
     * @throws \Exception
     */
    public function testClose()
    {
        $this->instance->log(PhalconLogger::CRITICAL);
        $this->assertTrue($this->instance->close());
    }

    /**
     * @throws \Exception
     */
    public function testSocketInitialize()
    {
        $socketInitialize = new \ReflectionMethod($this->instance, 'socketInitialize');
        $socketInitialize->setAccessible(true);
        $this->assertTrue($socketInitialize->invoke($this->instance));
        $host = new \ReflectionProperty($this->instance, 'host');
        $host->setAccessible(true);
        $host->setValue($this->instance, 'not.existing.host');
        $this->assertFalse($socketInitialize->invoke($this->instance));
    }

    /**
     * @throws \Exception
     */
    public function testLogWithIncorrectSocket()
    {
        $host = new \ReflectionProperty($this->instance, 'host');
        $host->setAccessible(true);
        $host->setValue($this->instance, 'not.existing.host');
        $this->assertInstanceOf(AdapterInterface::class,
            $this->instance->log(PhalconLogger::CRITICAL, 'Message', ['Context' => 'Array']));
    }

    /**
     * @throws \Exception
     */
    public function testLogWithErrorLevel()
    {
        $this->assertInstanceOf(AdapterInterface::class, $this->instance->debug('Message'));
        $this->assertInstanceOf(AdapterInterface::class, $this->instance->error('Message'));
        $this->assertInstanceOf(AdapterInterface::class, $this->instance->info('Message'));
        $this->assertInstanceOf(AdapterInterface::class, $this->instance->notice('Message'));
        $this->assertInstanceOf(AdapterInterface::class, $this->instance->warning('Message'));
        $this->assertInstanceOf(AdapterInterface::class, $this->instance->alert('Message'));
        $this->assertInstanceOf(AdapterInterface::class, $this->instance->emergency('Message'));
    }

    /**
     * @throws \Exception
     */
    public function testDestructor()
    {
        $socket = new \ReflectionProperty($this->instance, 'socket');
        $socket->setAccessible(true);
        $this->instance->__destruct();
        $this->assertNull($socket->getValue($this->instance));
    }
}
