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


    /**
     * Retrieve our host or unix socket that we're connected to
     *
     * @return string
     */
    public function getHost(): string
    {
        return $this->redis->getHost();
    }

    /**
     * Get the port we're connected to
     *
     * @return int
     */
    public function getPort(): int
    {
        return $this->redis->getPort();
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
