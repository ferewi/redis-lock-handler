<?php
use Jimtonic\RedisLockHandler\Handler\BlamedLockHandler;
use Jimtonic\RedisLockHandler\MyBlamedLockable;

/**
 * Created by PhpStorm.
 * User: ferdi
 * Date: 06.11.15
 * Time: 17:02
 */
class BlamedLockHandlerTest extends PHPUnit_Framework_TestCase
{
    /** @var  BlamedLockHandler */
    protected $handler;

    /** @var  PHPUnit_Framework_MockObject_MockObject */
    protected $redisClient;

    /** @var MyBlamedLockable */
    protected $lockable;

    protected function setUp()
    {
        parent::setUp();

        $this->redisClient =  $this->getMock('Predis\\Client', [
            'del',
            'exists',
            'expire',
            'get',
            'setnx',
        ]);

        $this->handler = new BlamedLockHandler($this->redisClient);
        $this->lockable = new MyBlamedLockable('my.lock', 'bob');
    }

    public function testLockIfPossible()
    {
        $this->redisClient->expects($this->once())
            ->method('setnx')
            ->with($this->lockable->getLockName(), $this->lockable->getUsername())
            ->willReturn("OK");
        $this->redisClient->expects($this->once())
            ->method('expire')
            ->willReturn(1);

        $this->assertTrue($this->handler->lock($this->lockable));
    }

    public function testLockIfNotPossible()
    {
        $this->redisClient->expects($this->once())
            ->method('setnx')
            ->with($this->lockable->getLockName(), $this->lockable->getUsername())
            ->willReturn(null);

        $this->assertFalse($this->handler->lock($this->lockable));
    }

    public function testGetLockedByIfLocked()
    {
        $this->redisClient->expects($this->once())
            ->method('get')
            ->with($this->lockable->getLockName())
            ->willReturn('alice');

        $this->assertEquals('alice', $this->handler->getLockedBy($this->lockable));
    }

    public function testGetLockedByIfUnlocked()
    {
        $this->redisClient->expects($this->once())
            ->method('get')
            ->with($this->lockable->getLockName())
            ->willReturn(null);

        $this->assertNull($this->handler->getLockedBy($this->lockable));
    }
}