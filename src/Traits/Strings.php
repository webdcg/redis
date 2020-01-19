<?php

namespace Webdcg\Redis\Traits;

trait Strings
{
    /**
     * Append specified string to the string stored in specified key.
     * See: https://redis.io/commands/append.
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
     * See: https://redis.io/commands/decr.
     *
     * @param  string $key
     *
     * @return int          the new value
     */
    public function decr(string $key): int
    {
        return $this->redis->decr($key);
    }

    /**
     * Decrement the number stored at key by the given decrement.
     * See: https://redis.io/commands/decrby.
     *
     * @param  string $key       [description]
     * @param  int    $decrement [description]
     *
     * @return int              The new value
     */
    public function decrBy(string $key, int $decrement): int
    {
        return $this->redis->decrBy($key, $decrement);
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

    /**
     * Increment the number stored at key by one.
     * See: https://redis.io/commands/incr.
     *
     * @param  string $key [description]
     *
     * @return int         The new value.
     */
    public function incr(string $key): int
    {
        return $this->redis->incr($key);
    }

    /**
     * Increment the number stored at key by the given increment.
     * See: https://redis.io/commands/incrby.
     *
     * @param  string $key       [description]
     * @param  int    $increment [description]
     *
     * @return int                  The new value
     */
    public function incrBy(string $key, int $increment): int
    {
        return $this->redis->incrBy($key, $increment);
    }

    /**
     * Increment the number stored at key by the given increment.
     * See: https://redis.io/commands/incrbyfloat.
     *
     * @param  string $key       [description]
     * @param  float  $increment [description]
     *
     * @return float                The new value.
     */
    public function incrByFloat(string $key, float $increment): float
    {
        return $this->redis->incrByFloat($key, $increment);
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
