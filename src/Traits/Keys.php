<?php

namespace Webdcg\Redis\Traits;

trait Keys
{
    /**
     * Remove specified keys [Blocking].
     *
     * @param  mixed $keys
     *
     * @return int Number of keys deleted
     */
    public function del(...$keys): int
    {
        return $this->redis->del(...$keys);
    }

    /**
     * Remove specified keys [Non Blocking].
     *
     * @param  mixed $keys
     *
     * @return int Number of keys deleted
     */
    public function delete(...$keys): int
    {
        return $this->redis->unlink(...$keys);
    }

    /**
     * Remove specified keys [NonBlocking].
     *
     * Note: If you are connecting to Redis server >= 4.0.0 you can remove a
     * key with the unlink method in the exact same way you would use del.
     * The Redis unlink command is non-blocking and will perform the actual
     *  deletion asynchronously.
     *
     * @param  mixed $keys
     *
     * @return int Number of keys deleted
     */
    public function unlink(...$keys): int
    {
        return $this->redis->unlink(...$keys);
    }

    /**
     * Return a serialized version of the value stored at the specified key.
     *
     * @param  string $key
     *
     * @return mixed|string|false       The Redis encoded value of the key,
     *                                  or FALSE if the key doesn't exist
     */
    public function dump(string $key)
    {
        return $this->redis->dump($key);
    }

    /**
     * Verify if the specified key exists.
     *
     * @param  mixed] $keys
     *
     * @return int
     */
    public function exists(...$keys): int
    {
        return $this->redis->exists(...$keys);
    }

    /**
     * Sets an expiration date (a timeout) on an item. pexpire requires a TTL in milliseconds.
     *
     * @param  string $key. The key that will disappear.
     * @param  int    $ttl. The key's remaining Time To Live, in seconds.
     *
     * @return bool         true in case of success, false in case of failure.
     */
    public function expire(string $key, int $ttl): bool
    {
        return $this->redis->expire($key, $ttl);
    }

    /**
     * Sets an expiration date (a timeout) on an item. pexpire requires a TTL in milliseconds.
     *
     * @param  string $key. The key that will disappear.
     * @param  int    $ttl. The key's remaining Time To Live, in seconds.
     *
     * @return bool         true in case of success, false in case of failure.
     */
    public function setTimeout(string $key, int $ttl): bool
    {
        return $this->redis->expire($key, $ttl);
    }

    /**
     * Sets an expiration date (a timeout) on an item. pexpire requires a TTL in milliseconds.
     *
     * @param  string $key. The key that will disappear.
     * @param  int    $ttl. The key's remaining Time To Live, in seconds.
     *
     * @return bool         true in case of success, false in case of failure.
     */
    public function pexpire(string $key, int $ttl): bool
    {
        return $this->redis->pexpire($key, $ttl);
    }

    /**
     * Sets an expiration date (a timestamp) on an item.
     *
     * @param  string $key  The key that will disappear.
     * @param  int    $ttl  Unix timestamp. The key's date of death, in seconds from Epoch time.
     *
     * @return bool         true in case of success, false in case of failure.
     */
    public function expireAt(string $key, int $ttl): bool
    {
        return $this->redis->expireAt($key, $ttl);
    }

    /**
     * Sets an expiration date (a timestamp) on an item in milliseconds.
     *
     * @param  string $key  The key that will disappear.
     * @param  int    $ttl  Unix timestamp. The key's date of death, in
     *                      milliseconds from Epoch time with.
     *
     * @return bool         true in case of success, false in case of failure.
     */
    public function pexpireAt(string $key, int $ttl): bool
    {
        return $this->redis->pexpireAt($key, $ttl);
    }

    /**
     * Returns the keys that match a certain pattern.
     *
     * @param  string $pattern  Pattern to match, using '*' as a wildcard.
     *
     * @return array            The keys that match a certain pattern.
     */
    public function keys(string $pattern): array
    {
        return $this->redis->keys($pattern);
    }

    /**
     * Returns the keys that match a certain pattern.
     *
     * @param  string $pattern  Pattern to match, using '*' as a wildcard.
     *
     * @return array            The keys that match a certain pattern.
     */
    public function getKeys(string $pattern): array
    {
        return $this->redis->keys($pattern);
    }

    /**
     * Scan the keyspace for keys.
     *
     * @param  [type]      $iterator
     * @param  string      $pattern
     * @param  int|int $count
     *
     * @return mixed|array|bool     This function will return an array of keys
     *                              or FALSE if Redis returned zero keys.
     */
    public function scan($iterator = null, string $pattern = '*', int $count = 10)
    {
        return $this->redis->scan($iterator, $pattern, $count);
    }

    /**
     * Migrates a key to a different Redis instance.
     * Note:: Redis introduced migrating multiple keys in 3.0.6, so you must
     * have at least that version in order to call migrate with an array of
     * keys.
     * See: https://redis.io/commands/migrate.
     *
     * @param  string $host         The destination host
     * @param  int    $port         The TCP port to connect to.
     * @param  array  $keys
     * @param  int    $db           The target DB.
     * @param  int    $timeout      The maximum amount of time given to this transfer.
     * @param  bool   $copy         (optional) Should we send the COPY flag to redis.
     * @param  bool   $replace      (optional) Should we send the REPLACE flag to redis.
     *
     * @return bool                 Simple string reply: The command returns OK
     *                              on success, or NOKEY if no keys were found
     *                              in the source instance.
     */
    public function migrate(
        string $host,
        int $port,
        array $keys,
        int $db,
        int $timeout,
        ?bool $copy = false,
        ?bool $replace = false
    ): bool {
        return $this->redis->migrate($host, $port, $keys, $db, $timeout);
    }

    /**
     * Moves a key to a different database.
     * See: https://redis.io/commands/move.
     *
     * @param  string $key  key, the key to move.
     * @param  int    $db   dbindex, the database number to move the key to.
     *
     * @return bool         TRUE in case of success, FALSE in case of failure.
     */
    public function move(string $key, int $db): bool
    {
        return $this->redis->move($key, $db);
    }

    /**
     * Describes the object pointed to by a key.
     * See: https://redis.io/commands/object.
     *
     * @param  string $subcommand       The information to retrieve.
     * @param  string $key              The key to fetch that data from.
     *
     * @return mixed|string|int|bool    STRING for "encoding", LONG for
     *                                  "refcount" and "idletime", FALSE if the
     *                                  key doesn't exist.
     */
    public function object(string $subcommand, string $key)
    {
        return $this->redis->object($subcommand, $key);
    }

    /**
     * Remove the expiration timer from a key.
     * See: https://redis.io/commands/persist.
     *
     * @param  string $key
     *
     * @return bool         TRUE if a timeout was removed, FALSE if the key
     *                      didn’t exist or didn’t have an expiration timer.
     */
    public function persist(string $key): bool
    {
        return $this->redis->persist($key);
    }

    /**
     * Returns a random key.
     * See: https://redis.io/commands/randomkey.
     *
     * @return string   An existing key in redis.
     */
    public function randomKey(): string
    {
        return $this->redis->randomKey();
    }

    /**
     * Renames key to newkey. It returns an error when key does not exist.
     * If newkey already exists it is overwritten.
     * See: https://redis.io/commands/rename.
     *
     * @param  string $key      Source key, the key to rename.
     * @param  string $newKey   Destination key, the new name for the key.
     *
     * @return bool             TRUE in case of success, FALSE in case of failure.
     */
    public function rename(string $key, string $newKey): bool
    {
        return $this->redis->rename($key, $newKey);
    }

    /**
     * Renames key to newkey. It returns an error when key does not exist.
     * If newkey already exists it is overwritten.
     * Note: renameKey is an alias for rename and will be removed in future versions of phpredis.
     * See: https://redis.io/commands/rename.
     *
     * @param  string $key      Source key, the key to rename.
     * @param  string $newKey   Destination key, the new name for the key.
     *
     * @return bool             TRUE in case of success, FALSE in case of failure.
     */
    public function renameKey(string $key, string $newKey): bool
    {
        return $this->redis->rename($key, $newKey);
    }

    /**
     * Renames key to newkey,. but will not replace a key if the destination
     * already exists. This is the same behaviour as setNx.
     * See: https://redis.io/commands/renamenx.
     *
     * @param  string $key      Source key, the key to rename.
     * @param  string $newKey   Destination key, the new name for the key.
     *
     * @return bool             TRUE in case of success, FALSE in case of failure.
     */
    public function renameNx(string $key, string $newKey): bool
    {
        return $this->redis->renameNx($key, $newKey);
    }

    /**
     * Returns the type of data pointed by a given key.
     * See: https://redis.io/commands/type.
     *
     * @param  string $key
     *
     * @return mixed        Depending on the type of the data pointed by the key,
     *                      this method will return the following value:
     *                      string: Redis::REDIS_STRING
     *                      set: Redis::REDIS_SET
     *                      list: Redis::REDIS_LIST
     *                      zset: Redis::REDIS_ZSET
     *                      hash: Redis::REDIS_HASH
     *                      other: Redis::REDIS_NOT_FOUND
     */
    public function type(string $key)
    {
        return $this->redis->type($key);
    }

    /**
     * Sort the elements in a list, set or sorted set.
     * See: https://redis.io/commands/sort.
     *
     * @param  string $key
     * @param  array $options   [key => value, ...] - optional, with the
     *                          following keys and values:
     *                          'by' => 'some_pattern_*',
     *                          'limit' => [0, 1],
     *                          'get' => 'some_other_pattern_*' or an array of patterns,
     *                          'sort' => 'asc' or 'desc',
     *                          'alpha' => TRUE,
     *                          'store' => 'external-key'
     *
     * @return array            An array of values, or a number corresponding
     *                          to the number of elements stored if that was used.
     */
    public function sort(string $key, array $options = []): array
    {
        if (empty($options)) {
            return $this->redis->sort($key);
        }

        if (!$this->is_associative($pairs)) {
            throw new NotAssociativeArrayException('The array provided is not associative.', 1);
        }

        return $this->redis->sort($key, $options);
    }

    /**
     * Returns the time to live left for a given key in seconds (ttl).
     * See: https://redis.io/commands/ttl.
     *
     * @param  string $key
     *
     * @return int          The time to live in seconds. If the key has no ttl,
     *                      -1 will be returned, and -2 if the key doesn't exist.
     */
    public function ttl(string $key): int
    {
        return $this->redis->ttl($key);
    }

    /**
     * Returns the time to live left for a given key in milliseconds (pttl).
     * See: https://redis.io/commands/pttl.
     *
     * @param  string $key
     *
     * @return int          The time to live in seconds. If the key has no ttl,
     *                      -1 will be returned, and -2 if the key doesn't exist.
     */
    public function pttl(string $key): int
    {
        return $this->redis->pttl($key);
    }

    /**
     * Restore a key from the result of a DUMP operation.
     * See: https://redis.io/commands/restore.
     *
     * @param  string $key   The key name
     * @param  int    $ttl   How long the key should live (if zero, no expire will be set on the key)
     * @param  string $value String (binary). The Redis encoded key value (from DUMP)
     *
     * @return mixed
     */
    public function restore(string $key, int $ttl = 0, string $value = '')
    {
        return $this->redis->restore($key, $ttl, $value);
    }
}
