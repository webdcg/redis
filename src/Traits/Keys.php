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
     * @param  [type]      $iterator [description]
     * @param  string      $pattern  [description]
     * @param  int|int $count    [description]
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
     * @param  array  $keys         [description]
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

    public function object(): bool
    {
        return false;
    }

    public function persist(): bool
    {
        return false;
    }

    public function randomKey(): bool
    {
        return false;
    }

    public function rename(): bool
    {
        return false;
    }

    public function renameKey(): bool
    {
        return false;
    }

    public function renameNx(): bool
    {
        return false;
    }

    public function type(): bool
    {
        return false;
    }

    public function sort(): bool
    {
        return false;
    }

    public function ttl(): bool
    {
        return false;
    }

    public function pttl(): bool
    {
        return false;
    }

    public function restore(): bool
    {
        return false;
    }
}
