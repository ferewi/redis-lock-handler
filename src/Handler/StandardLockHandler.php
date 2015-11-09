<?php
/**
 * Created by PhpStorm.
 * User: ferdi
 * Date: 06.11.15
 * Time: 13:39
 */

namespace Jimtonic\RedisLockHandler\Handler;


use Jimtonic\RedisLockHandler\LockableInterface;

/**
 * Class StandardLockHandler
 * @package Jimtonic\RedisLockHandler
 */
class StandardLockHandler extends AbstractLockHandler
{

    /**
     * Lock the supplied Lockable.
     *
     * @param LockableInterface $lockable
     * @param int $timeout
     *
     * @return boolean
     */
    public function lock(LockableInterface $lockable, $timeout = 1800)
    {
        $result = $this->client->setnx($lockable->getLockName(), true);
        if ($result == "OK") {
            $timeout = $this->client->expire($lockable->getLockName(), $timeout);
        }

        return $result == "OK" && $timeout == 1;
    }


}