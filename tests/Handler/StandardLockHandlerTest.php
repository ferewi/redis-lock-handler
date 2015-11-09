<?php
namespace Jimtonic\RedisLockHandler;


use Jimtonic\RedisLockHandler\Handler\StandardLockHandler;
use PHPUnit_Framework_MockObject_MockObject;
use PHPUnit_Framework_TestCase;

/**
 * Created by PhpStorm.
 * User: ferdi
 * Date: 06.11.15
 * Time: 13:48
 */
class StandardLockHandlerTest extends PHPUnit_Framework_TestCase
{
    /** @var  StandardLockHandler */
    protected $handler;

    /** @var  PHPUnit_Framework_MockObject_MockObject */
    protected $redisClient;

    /** @var MyLockable */
    protected $lockable;

    protected function setUp()
    {
        parent::setUp();

        $this->redisClient =  $this->getMock('Predis\\Client', array(
            'del',
            'exists',
            'expire',
            'setnx',
        ));

        $this->handler = new Handler\StandardLockHandler($this->redisClient);
        $this->lockable = new MyLockable('my.lock');
    }

    public function testCanLockIfCould()
    {
        $this->redisClient->expects($this->once())
            ->method('exists')
            ->with($this->lockable->getLockName())
            ->willReturn(false);

        $this->assertTrue($this->handler->canLock($this->lockable));
    }

    public function testCanLockIfCouldNot()
    {
        $this->redisClient->expects($this->once())
            ->method('exists')
            ->willReturn(true);

        $this->assertFalse($this->handler->canLock($this->lockable));
    }

    public function testLockIfPossible()
    {
        $this->redisClient->expects($this->once())
            ->method('setnx')
            ->with($this->lockable->getLockName(), true)
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
            ->with($this->lockable->getLockName(), true)
            ->willReturn(null);

        $this->assertFalse($this->handler->lock($this->lockable));
    }

    public function testReleaseLockIfExists()
    {
        $this->redisClient->expects($this->once())
            ->method('del')
            ->with($this->lockable->getLockName())
            ->willReturn(1);

        $this->assertTrue($this->handler->release($this->lockable));
    }

    public function testReleaseLockIfNotExists()
    {
        $this->redisClient->expects($this->once())
            ->method('del')
            ->with($this->lockable->getLockName())
            ->willReturn(0);

        $this->assertTrue($this->handler->release($this->lockable));
    }

}