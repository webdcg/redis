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
     * @return string       The host or unix socket we're connected to or FALSE
     *                      if we're not connected
     */
    public function getHost(): string
    {
        return $this->redis->getHost();
    }

    /**
     * Get the port we're connected to
     *
     * @return int          Returns the port we're connected to or FALSE if
     *                      we're not connected
     */
    public function getPort(): int
    {
        return $this->redis->getPort();
    }

    /**
     * Get the database number phpredis is pointed to
     *
     * @return int          Returns the database number (LONG) phpredis thinks
     *                      it's pointing to or FALSE if we're not connected.
     */
    public function getDbNum(): int
    {
        return $this->redis->getDbNum();
    }

    /**
     * Get the (write) timeout in use for phpredis
     *
     * @return float        The timeout (DOUBLE) specified in our connect call
     *                      or FALSE if we're not connected.
     */
    public function getTimeout(): float
    {
        return $this->redis->getTimeout();
    }

    /**
     * Get the read timeout specified to phpredis or FALSE if we're not connected
     *
     * @return int          Returns the read timeout (which can be set using
     *                      setOption and Redis::OPT_READ_TIMEOUT) or FALSE if
     *                      we're not connected.
     */
    public function getReadTimeout(): int
    {
        return $this->redis->getReadTimeout();
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
