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
    public function hDel(string $key, ...$fields): bool
    {
        return $this->redis->hDel($key, $fields);
    }

    public function hExists(): bool
    {
        return false;
    }

    public function hGet(): bool
    {
        return false;
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

    public function hLen(): bool
    {
        return false;
    }

    public function hMGet(): bool
    {
        return false;
    }

    public function hMSet(): bool
    {
        return false;
    }

    public function hSet(): bool
    {
        return false;
    }

    public function hSetNx(): bool
    {
        return false;
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
