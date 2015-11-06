<?php
/**
 * Created by PhpStorm.
 * User: ferdi
 * Date: 06.11.15
 * Time: 16:50
 */

namespace Jimtonic\RedisLockHandler;


interface BlamedLockableInterface extends LockableInterface
{
    public function getUsername();
}