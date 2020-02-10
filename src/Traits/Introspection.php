<?php

/**
 *  #     #               ######   #####   #####
 *  #  #  # ###### #####  #     # #     # #     #
 *  #  #  # #      #    # #     # #       #
 *  #  #  # #####  #####  #     # #       #  ####
 *  #  #  # #      #    # #     # #       #     #
 *  #  #  # #      #    # #     # #     # #     #
 *   ## ##  ###### #####  ######   #####   #####
 */

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
     * @return mixed|string|bool    The host or unix socket we're connected to
     *                              or FALSE if we're not connected.
     */
    public function getHost()
    {
        return $this->redis->getHost();
    }


    /**
     * Get the port we're connected to
     *
     * @return mixed|int|bool       Returns the port we're connected to or
     *                              FALSE if we're not connected.
     */
    public function getPort()
    {
        return $this->redis->getPort();
    }


    /**
     * Get the database number phpredis is pointed to
     *
     * @return mixed|int|bool          Returns the database number (LONG)
     *                                 phpredis thinks it's pointing to or
     *                                 FALSE if we're not connected.
     */
    public function getDbNum()
    {
        return $this->redis->getDbNum();
    }


    /**
     * Get the (write) timeout in use for phpredis
     *
     * @return mixed|float|bool     The timeout (DOUBLE) specified in our
     *                              connect call or FALSE if we're not
     *                              connected.
     */
    public function getTimeout()
    {
        return $this->redis->getTimeout();
    }


    /**
     * Get the read timeout specified to phpredis or FALSE if we're not connected
     *
     * @return mixed|int|bool       Returns the read timeout (which can be set
     *                              using setOption and Redis::OPT_READ_TIMEOUT)
     *                              or FALSE if we're not connected.
     */
    public function getReadTimeout()
    {
        return $this->redis->getReadTimeout();
    }


    /**
     * Gets the persistent ID that phpredis is using.
     *
     * ToDo: The tests verify that this method is not working properly using
     *       PHPRedis when there's an actual persistent connection open, the
     *       rest of the tests were successful.
     *
     * @return mixed|int|null|bool  Returns the persistent id phpredis is using
     *                              (which will only be set if connected with
     *                              pconnect), NULL if we're not using a
     *                              persistent ID, and FALSE if we're not
     *                              connected.
     */
    public function getPersistentID()
    {
        return false;
    }

    public function getAuth(): bool
    {
        return false;
    }
}
