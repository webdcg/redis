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
}
