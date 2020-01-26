<?php

namespace Webdcg\Redis\Traits;

trait Hashes
{
    /**
     * Removes a value from the hash stored at key. If the hash table doesn't
     * exist, or the key doesn't exist, FALSE is returned.
     * See: https://redis.io/commands/hdel.
     *
     * @param  string $key
     * @param  splat $fields
     *
     * @return int          LONG the number of deleted keys, 0 if the key doesn't
     *                      exist, FALSE if the key isn't a hash.
     */
    public function hDel(string $key, ...$fields): int
    {
        return $this->redis->hDel($key, ...$fields);
    }

    public function hExists(): bool
    {
        return false;
    }

    /**
     * Gets a value from the hash stored at key. If the hash table doesn't
     * exist, or the key doesn't exist, FALSE is returned.
     * See: https://redis.io/commands/hget.
     *
     * @param  string $key
     * @param  string $field
     *
     * @return string       STRING The value, if the command executed successfully
     *                      BOOL FALSE in case of failure
     */
    public function hGet(string $key, string $field)
    {
        return $this->redis->hGet($key, $field);
    }

    public function hGetAll(): bool
    {
        return false;
    }

    public function hIncrBy(): bool
    {
        return false;
    }

    public function hIncrByFloat(): bool
    {
        return false;
    }

    public function hKeys(): bool
    {
        return false;
    }

    /**
     * Returns the length of a hash, in number of items.
     * See: https://redis.io/commands/hlen.
     *
     * @param  string $key
     *
     * @return int          LONG the number of items in a hash, FALSE if the
     *                      key doesn't exist or isn't a hash.
     */
    public function hLen(string $key): int
    {
        return $this->redis->hLen($key);
    }

    public function hMGet(): bool
    {
        return false;
    }

    public function hMSet(): bool
    {
        return false;
    }

    /**
     * Adds a value to the hash stored at key.
     * See: https://redis.io/commands/hset.
     *
     * @param  string $key
     * @param  string $field
     * @param  string $value
     *
     * @return int          LONG 1 if value didn't exist and was added
     *                      successfully, 0 if the value was already present
     *                      and was replaced, FALSE if there was an error.
     */
    public function hSet(string $key, string $field, string $value): int
    {
        return $this->redis->hSet($key, $field, $value);
    }

    /**
     * Adds a value to the hash stored at key only if this field isn't already
     * in the hash.
     * See: https://redis.io/commands/hsetnx.
     *
     * @param  string $key
     * @param  string $field
     * @param  string $value
     *
     * @return bool             TRUE if the field was set, FALSE if it was
     *                          already present.
     */
    public function hSetNx(string $key, string $field, string $value): bool
    {
        return $this->redis->hSetNx($key, $field, $value);
    }

    public function hVals(): bool
    {
        return false;
    }

    public function hScan(): bool
    {
        return false;
    }

    public function hStrLen(): bool
    {
        return false;
    }
}
