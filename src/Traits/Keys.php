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
}
