<?php
/**
 * Created by PhpStorm.
 * User: ferdi
 * Date: 06.11.15
 * Time: 16:55
 */

namespace Jimtonic\RedisLockHandler\Handler;


use Jimtonic\RedisLockHandler\BlamedLockableInterface;

class BlamedLockHandler extends AbstractLockHandler
{
    /**
     * Lock the supplied Lockable.
     *
     * @param BlamedLockableInterface $lockable
     * @param int $timeout
     *
     * @return boolean
     */
    public function lock(BlamedLockableInterface $lockable, $timeout = 1800)
    {
        $result = $this->client->setnx($lockable->getLockName(), $lockable->getUsername());
        if ($result == "OK") {
            $timeout = $this->client->expire($lockable->getLockName(), $timeout);
        }

        return $result == "OK" && $timeout == 1;
    }

    public function getLockedBy(BlamedLockableInterface $lockable)
    {
        $result = $this->client->get($lockable->getLockName());

        return $result;
    }

}