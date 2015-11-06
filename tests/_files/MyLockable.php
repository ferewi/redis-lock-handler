<?php

namespace Jimtonic\RedisLockHandler;

/**
 * Class MyLockable
 */
class MyLockable implements \Jimtonic\RedisLockHandler\LockableInterface
{
    /** @var string */
    private $lockname;

    /**
     * @param string $lockname
     */
    public function __construct($lockname)
    {
        $this->lockname = $lockname;
    }
    /**
     * {inheritdoc}
     */
    public function getLockName()
    {
        return $this->lockname;
    }

}