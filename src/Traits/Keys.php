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
     * Remove specified keys [Blocking].
     *
     * @param  mixed $keys
     *
     * @return int Number of keys deleted
     */
    public function delete(...$keys): int
    {
        return $this->redis->del(...$keys);
    }
}
