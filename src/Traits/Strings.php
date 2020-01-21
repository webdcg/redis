<?php

namespace Webdcg\Redis\Traits;

use Webdcg\Redis\Exceptions\NotAssociativeArrayException;

function is_associative(array $array)
{
    return array_keys($array) !== range(0, count($array) - 1);
}

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

    /**
     * Get the value related to the specified key.
     * See: https://redis.io/commands/get.
     *
     * @param  string $key [description]
     *
     * @return mixed|string|bool    If key didn't exist, FALSE is returned.
     *                              Otherwise, the value related to this key
     *                              is returned.
     */
    public function get(string $key)
    {
        return $this->redis->get($key);
    }

    /**
     * Return a substring of a larger string.
     * See: https://redis.io/commands/getrange.
     *
     * @param  string $key   [description]
     * @param  int    $start [description]
     * @param  int    $end   [description]
     *
     * @return string       the substring
     */
    public function getRange(string $key, int $start, int $end): string
    {
        return $this->redis->getRange($key, $start, $end);
    }

    /**
     * Atomically sets key to value and returns the old value stored at key.
     * Returns an error when key exists but does not hold a string value.
     * See: https://redis.io/commands/getset.
     *
     * @param  string $key   [description]
     * @param  string $value [description]
     *
     * @return string       Bulk string reply: the old value stored at key,
     *                      or nil when key did not exist.
     */
    public function getSet(string $key, string $value): string
    {
        return $this->redis->getSet($key, $value);
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

    /**
     * Get the values of all the specified keys. If one or more keys don't
     * exist, the array will contain FALSE at the position of the key.
     * See: https://redis.io/commands/mget.
     *
     * @param  array  $keys [description]
     *
     * @return array        Array reply: list of values at the specified keys.
     */
    public function mGet(array $keys): array
    {
        return $this->redis->mGet($keys);
    }

    /**
     * Get the values of all the specified keys. If one or more keys don't
     * exist, the array will contain FALSE at the position of the key.
     * See: https://redis.io/commands/mget.
     *
     * @param  array  $keys [description]
     *
     * @return array        Array reply: list of values at the specified keys.
     */
    public function getMultiple(array $keys): array
    {
        return $this->redis->mGet($keys);
    }

    /**
     * Sets multiple key-value pairs in one atomic command.
     * See: https://redis.io/commands/mset.
     *
     * @param  array  $pairs [description]
     *
     * @return bool         TRUE in case of success, FALSE in case of failure.
     */
    public function mSet(array $pairs): bool
    {
        if (! is_associative($pairs)) {
            throw new NotAssociativeArrayException('The array provided is not associative.', 1);
        }

        return $this->redis->mSet($pairs);
    }

    /**
     * Sets multiple key-value pairs in one atomic command. MSETNX only returns
     * TRUE if all the keys were set (see SETNX).
     * See: https://redis.io/commands/msetnx.
     *
     * @param  array  $pairs [description]
     *
     * @return bool         TRUE in case of success, FALSE in case of failure.
     */
    public function mSetNX(array $pairs): bool
    {
        if (! is_associative($pairs)) {
            throw new NotAssociativeArrayException('The array provided is not associative.', 1);
        }
        
        return $this->redis->mSetNX($pairs);
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

    /**
     * Changes a substring of a larger string.
     * See: https://redis.io/commands/setrange.
     *
     * @param string $key    [description]
     * @param int    $offset [description]
     * @param string $value  [description]
     *
     * @return int          the length of the string after it was modified.
     */
    public function setRange(string $key, int $offset, string $value): int
    {
        return $this->redis->setRange($key, $offset, $value);
    }

    /**
     * Returns the length of the string value stored at key. An error is
     * returned when key holds a non-string value.
     * See: https://redis.io/commands/strlen.
     *
     * @param  string $key [description]
     *
     * @return int          The length of the string at key, or 0 when key does
     *                      not exist.
     */
    public function strLen(string $key): int
    {
        return $this->redis->strLen($key);
    }
}
