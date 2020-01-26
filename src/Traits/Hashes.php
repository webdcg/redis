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

    /**
     * Verify if the specified member exists in a key.
     * See: https://redis.io/commands/hexists.
     *
     * @param  string $key
     * @param  string $field
     *
     * @return bool             If the member exists in the hash table, return
     *                          TRUE, otherwise return FALSE.
     */
    public function hExists(string $key, string $field): bool
    {
        return $this->redis->hExists($key, $field);
    }

    /**
     * Gets a value from the hash stored at key. If the hash table doesn't
     * exist, or the key doesn't exist, FALSE is returned.
     * See: https://redis.io/commands/hget.
     *
     * @param  string $key
     * @param  string $field
     *
     * @return mixed       STRING The value, if the command executed successfully
     *                      BOOL FALSE in case of failure
     */
    public function hGet(string $key, string $field)
    {
        return $this->redis->hGet($key, $field);
    }

    /**
     * Returns the whole hash, as an array of strings indexed by strings.
     * The order is random and corresponds to redis' own internal
     * representation of the set structure.
     * See: https://redis.io/commands/hgetall.
     *
     * @param  string $key
     *
     * @return array        An array of elements, the contents of the hash.
     */
    public function hGetAll(string $key): array
    {
        return $this->redis->hGetAll($key);
    }

    /**
     * Increments the value of a member from a hash by a given amount.
     * See: https://redis.io/commands/hincrby.
     *
     * @param  string      $key
     * @param  string      $field       (integer) value that will be added to
     *                                  the member's value
     * @param  int|integer $increment
     *
     * @return int                      LONG the new value
     */
    public function hIncrBy(string $key, string $field, int $increment = 1): int
    {
        return $this->redis->hIncrBy($key, $field, $increment);
    }

    /**
     * Increments the value of a hash member by the provided float value.
     * See: https://redis.io/commands/hincrbyfloat.
     *
     * @param  string $key
     * @param  string $field
     * @param  float  $increment    (float) value that will be added to the
     *                              member's value
     *
     * @return float                the new value
     */
    public function hIncrByFloat(string $key, string $field, float $increment = 1.0): float
    {
        return $this->redis->hIncrByFloat($key, $field, $increment);
    }

    /**
     * Returns the keys in a hash, as an array of strings.
     * The order is random and corresponds to redis' own internal
     * representation of the set structure.
     * See: https://redis.io/commands/hkeys.
     *
     * @param  string $key
     *
     * @return array        An array of elements, the keys of the hash.
     *                      This works like PHP's array_keys().
     */
    public function hKeys(string $key): array
    {
        return $this->redis->hKeys($key);
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

    /**
     * Retrieve the values associated to the specified fields in the hash.
     * See: https://redis.io/commands/hmget.
     *
     * @param  string $key
     * @param  array  $fields
     *
     * @return array            An array of elements, the values of the
     *                          specified fields in the hash, with the hash
     *                          keys as array keys.
     */
    public function hMGet(string $key, array $fields): array
    {
        return $this->redis->hMGet($key, $fields);
    }

    /**
     * Fills in a whole hash. Non-string values are converted to string, using
     * the standard (string) cast. NULL values are stored as empty strings.
     * See: https://redis.io/commands/hmset.
     *
     * @param  string $key
     * @param  array  $hash key → value array
     *
     * @return bool         TRUE if the field was set, FALSE if it was
     *                      already present.
     */
    public function hMSet(string $key, array $hash): bool
    {
        return $this->redis->hMSet($key, $hash);
    }

    /**
     * Adds a value to the hash stored at key.
     * See: https://redis.io/commands/hset.
     *
     * @param  string $key
     * @param  string $field
     * @param  mixed $value
     *
     * @return int          LONG 1 if value didn't exist and was added
     *                      successfully, 0 if the value was already present
     *                      and was replaced, FALSE if there was an error.
     */
    public function hSet(string $key, string $field, $value): int
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

    /**
     * Returns the values in a hash, as an array of strings.
     * The order is random and corresponds to redis' own internal
     * representation of the set structure.
     * See: https://redis.io/commands/hvals.
     *
     * @param  string $key
     *
     * @return array        An array of elements, the values of the hash.
     *                      This works like PHP's array_values().
     */
    public function hVals(string $key): array
    {
        return $this->redis->hVals($key);
    }

    public function hScan(): bool
    {
        return false;
    }

    /**
     * Get the string length of the value associated with field in the hash
     * stored at key.
     * See: https://redis.io/commands/hstrlen.
     *
     * @param  string $key
     * @param  string $field
     *
     * @return int              LONG the string length of the value associated
     *                          with field, or zero when field is not present
     *                          in the hash or key does not exist at all.
     */
    public function hStrLen(string $key, string $field): int
    {
        return $this->redis->hStrLen($key, $field);
    }
}
