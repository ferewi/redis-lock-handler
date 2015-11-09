<?php
/**
 * Created by PhpStorm.
 * User: ferdi
 * Date: 06.11.15
 * Time: 18:32
 */

namespace Jimtonic\RedisLockHandler\Handler;

use Jimtonic\RedisLockHandler\BlamedLockableInterface;
use Jimtonic\RedisLockHandler\Exception\ParameterException;
use Jimtonic\RedisLockHandler\LockableInterface;
use Predis\Client;

class Factory
{
    /** @var array  */
    protected $types = array(
        'std' => 'Jimtonic\\RedisLockHandler\\Handler\\StandardLockHandler',
        'blamed' => 'Jimtonic\\RedisLockHandler\\Handler\\BlamedLockHandler'
    );

    /** @var array  */
    protected $schemes = array('tcp', 'redis', 'unix');

    /**
     * Creates a new LockHandler depending on the given arguments.
     *
     * @param string $host
     * @param string $port
     * @param string $scheme
     * @param string $type
     * @param mixed $lockable
     *
     * @return mixed
     */
    public static function create()
    {
        $factory = new self();
        $params = $factory->resolveArguments(func_get_args());

        $client = new Client(array(
            'host' => $params['host'],
            'port' => $params['port'],
            'scheme' => $params['scheme']
        ));

        $handler = new $params['type']($client);
        return $handler;
    }

    /**
     * @param $args
     * @return array
     * @throws ParameterException
     */
    private function resolveArguments($args)
    {
        $host = null;
        $port = null;
        $scheme = null;
        $type = null;
        $lockable = null;
        foreach ($args as $arg) {
            if (is_string($arg)) {
                if (preg_match("/\d+\.\d+\.\d+\.\d+/s", $arg)) {
                    $host = $arg;
                } elseif (is_numeric($arg)) {
                    $port = $arg;
                } elseif (in_array($arg, $this->schemes)) {
                    $scheme = $arg;
                } elseif (array_key_exists($arg, $this->types)) {
                    $type = $this->types[$arg];
                } else {
                    throw new ParameterException('The argument cannot be resolved as host, port, scheme or type.');
                }
            } else if ($arg instanceof BlamedLockableInterface) {
                $type = $this->types['blamed'];
            } else if ($arg instanceof LockableInterface) {
                $type = $this->types['std'];
            } else {
                throw new ParameterException('The argument cannot be resolved as host, port, scheme or type.');
            }
        }

        $params = array(
            'host' => $host !== null ? $host : '127.0.0.1',
            'port' => $port !== null ? $port : '6379',
            'scheme' => $scheme !== null ? $scheme : 'redis',
            'type' => $type !== null ? $type : $this->types['std'],
        );

        return $params;
    }

}