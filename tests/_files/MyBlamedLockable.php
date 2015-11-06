<?php
/**
 * Created by PhpStorm.
 * User: ferdi
 * Date: 06.11.15
 * Time: 17:06
 */

namespace Jimtonic\RedisLockHandler;


class MyBlamedLockable extends MyLockable implements BlamedLockableInterface
{
    /** @var string */
    private $username;

    /**
     * MyBlamedLockable constructor.
     * @param string $lockname
     * @param string $username
     */
    public function __construct($lockname, $username)
    {
        parent::__construct($lockname);
        $this->username = $username;
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

}