<?php

namespace Webdcg\Redis\Traits;

trait Introspection
{
    /**
     * A method to determine if a phpredis object thinks it's connected to a
     * server.
     *
     * @return boolean      Returns TRUE if phpredis thinks it's connected
     *                      and FALSE if not.
     */
    public function isConnected(): bool
    {
        return $this->redis->isConnected();
    }

    public function getHost(): bool
    {
        return false;
    }

    public function getPort(): bool
    {
        return false;
    }

    public function getDbNum(): bool
    {
        return false;
    }

    public function getTimeout(): bool
    {
        return false;
    }

    public function getReadTimeout(): bool
    {
        return false;
    }

    public function getPersistentID(): bool
    {
        return false;
    }

    public function getAuth(): bool
    {
        return false;
    }
}
