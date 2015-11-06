<?php
/**
 * Created by PhpStorm.
 * User: ferdi
 * Date: 06.11.15
 * Time: 17:15
 */

namespace Jimtonic\RedisLockHandler\Handler;


use Jimtonic\RedisLockHandler\LockableInterface;
use Predis\Client;

abstract class AbstractLockHandler
{
    /** @var \Predis\Client  */
    protected $client;

    /**
     * @param \Predis\Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * Test if the Lockable can be locked.
     *
     * @param LockableInterface $lockable
     * @return boolean
     */
    public function canLock(LockableInterface $lockable)
    {
        return !$this->client->exists($lockable->getLockName());
    }

    /**
     * Release a lock.
     *
     * @param LockableInterface|null $lockable
     *
     * @return boolean
     */
    public function release(LockableInterface $lockable = null)
    {
        return $this->client->del($lockable->getLockName()) >= 0;
    }
}