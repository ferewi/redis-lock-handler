<?php
/**
 * Created by PhpStorm.
 * User: ferdi
 * Date: 06.11.15
 * Time: 13:41
 */

namespace Jimtonic\RedisLockHandler;


interface LockableInterface
{
    /**
     * Get the name of the lock.
     *
     * @return string
     */
    public function getLockName();
}