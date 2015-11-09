<?php

use Jimtonic\RedisLockHandler\Exception\ParameterException;
use Jimtonic\RedisLockHandler\Handler\Factory as LockHandler;

class FactoryTestextends extends  PHPUnit_Framework_TestCase
{
    public function testCreateWithoutArguments()
    {
        $handler = LockHandler::create();

        $this->assertInstanceOf('Jimtonic\RedisLockHandler\Handler\StandardLockHandler', $handler);
    }

    public function testCreateWithHost()
    {
        $handler = LockHandler::create('192.168.99.100');
        $this->assertInstanceOf('Jimtonic\RedisLockHandler\Handler\StandardLockHandler', $handler);
    }

    public function testCreateWithHostAndPort()
    {
        $handler = LockHandler::create('192.168.99.100', '9999');
        $this->assertInstanceOf('Jimtonic\RedisLockHandler\Handler\StandardLockHandler', $handler);
    }

    public function testCreateWithHostAndPortAndScheme()
    {
        $handler = LockHandler::create('192.168.99.100', '9999', 'tcp');
        $this->assertInstanceOf('Jimtonic\RedisLockHandler\Handler\StandardLockHandler', $handler);
    }

    public function testCreateWithHostAndPortAndSchemeAndTypeStd()
    {
        $handler = LockHandler::create('192.168.99.100', '9999', 'tcp', 'std');
        $this->assertInstanceOf('Jimtonic\RedisLockHandler\Handler\StandardLockHandler', $handler);
    }

    public function testCreateWithHostAndPortAndSchemeAndTypeBlamed()
    {
        $handler = LockHandler::create('192.168.99.100', '9999', 'tcp', 'blamed');
        $this->assertInstanceOf('Jimtonic\RedisLockHandler\Handler\BlamedLockHandler', $handler);
    }

    public function testCreateWithHostAndPortAndSchemeAndLockable()
    {
        $lockable = new \Jimtonic\RedisLockHandler\MyLockable('my.lock');
        $handler = LockHandler::create('192.168.99.100', '9999', 'tcp', $lockable);
        $this->assertInstanceOf('Jimtonic\RedisLockHandler\Handler\StandardLockHandler', $handler);
    }

    public function testCreateWithHostAndPortAndSchemeAndBlamedLockable()
    {
        $lockable = new \Jimtonic\RedisLockHandler\MyBlamedLockable('my.lock', 'bob');
        $handler = LockHandler::create('192.168.99.100', '9999', 'tcp', $lockable);
        $this->assertInstanceOf('Jimtonic\RedisLockHandler\Handler\BlamedLockHandler', $handler);
    }

    /**
     * @expectedException Jimtonic\RedisLockHandler\Exception\ParameterException
     */
    public function testException()
    {
        $handler = LockHandler::create('foo');
    }

    /**
     * @expectedException Jimtonic\RedisLockHandler\Exception\ParameterException
     */
    public function testExceptionWithObject()
    {
        $object = new stdClass();
        $handler = LockHandler::create($object);
    }


}