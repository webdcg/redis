<?php

namespace Webdcg\Redis\Traits;

trait Strings
{
    /**
     * Append specified string to the string stored in specified key.
     *
     * @param  string $key
     * @param  string $value
     *
     * @return int              Size of the value after the append.
     */
    public function append(string $key, string $value): int
    {
        return $this->redis->append($key, $value);
    }

    /**
     * Decrement the number stored at key by one.
     *
     * @param  string $key
     *
     * @return int          the new value
     */
    public function decr(string $key): int
    {
        return $this->redis->decr($key);
    }

    public function decrBy(): bool
    {
        return false;
    }

    public function get(string $key)
    {
        return $this->redis->get($key);
    }

    public function getRange(): bool
    {
        return false;
    }

    public function getSet(): bool
    {
        return false;
    }

    public function incr(): bool
    {
        return false;
    }

    public function incrBy(): bool
    {
        return false;
    }

    public function incrByFloat(): bool
    {
        return false;
    }

    public function mGet(): bool
    {
        return false;
    }

    public function getMultiple(): bool
    {
        return false;
    }

    public function mSet(): bool
    {
        return false;
    }

    public function mSetNX(): bool
    {
        return false;
    }

    /**
     * Set the string value in argument as value of the key. If you're using Redis >= 2.6.12, you can pass extended
     * options as explained below.
     *
     * @param string $key
     * @param mixed $value
     * @param mixed $args Timeout or Options Array (optional). If you pass an
     *                    integer, phpredis will redirect to SETEX, and will
     *                    try to use Redis >= 2.6.12 extended options if you
     *                    pass an array with valid values
     *
     * @return bool TRUE if the command is successful.
     */
    public function set(string $key, $value, ...$args): bool
    {
        if (empty($args)) {
            return $this->redis->set($key, $value);
        }

        return $this->redis->set($key, $value, $args[0]);
    }

    public function setEx(string $key, int $timeout, $value): bool
    {
        return $this->redis->setEx($key, $timeout, $value);
    }

    public function pSetEx(): bool
    {
        return false;
    }

    public function setNx(): bool
    {
        return false;
    }

    public function setRange(): bool
    {
        return false;
    }

    public function strLen(): bool
    {
        return false;
    }
}
