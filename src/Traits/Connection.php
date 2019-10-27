<?php

namespace Webdcg\Redis\Traits;

trait Connection
{
    /**
     * Connects to a Redis instance.
     *
     * @param  string       $host           can be a host, or the path to a unix domain socket. Starting from
     *                                       version 5.0.0 it is possible to specify
     * @param  int|int      $port           optional defaults to 6379
     * @param  float|int    $timeout        value in seconds (optional, default is 0 meaning unlimited)
     * @param  null         $reserved       should be NULL if retry_interval is specified
     * @param  int|int      $retry_interval value in milliseconds (optional)
     * @param  float|int    $read_timeout   value in seconds (optional, default is 0 meaning unlimited)
     *
     * @return bool     true on success, false on error
     *
     * @throws RedisException
     */
    public function connect(
        string $host = '127.0.0.1',
        int $port = 6379,
        float $timeout = 0,
        $reserved = null,
        int $retry_interval = 0,
        float $read_timeout = 0
    ) : bool {
        return $this->redis->connect($host, $port, $timeout, $reserved, $retry_interval, $read_timeout);
    }

    /**
     * Connects to a Redis instance.
     *
     * @param  string       $host           can be a host, or the path to a unix domain socket. Starting from
     *                                       version 5.0.0 it is possible to specify
     * @param  int|int      $port           optional defaults to 6379
     * @param  float|int    $timeout        value in seconds (optional, default is 0 meaning unlimited)
     * @param  null         $reserved       should be NULL if retry_interval is specified
     * @param  int|int      $retry_interval value in milliseconds (optional)
     * @param  float|int    $read_timeout   value in seconds (optional, default is 0 meaning unlimited)
     *
     * @return bool     true on success, false on error
     *
     * @throws RedisException
     */
    public function open(
        string $host = '127.0.0.1',
        int $port = 6379,
        float $timeout = 0,
        $reserved = null,
        int $retry_interval = 0,
        float $read_timeout = 0
    ) : bool {
        return $this->connect($host, $port, $timeout, $reserved, $retry_interval, $read_timeout);
    }

    /**
     * Connects to a Redis instance or reuse a connection already established with pconnect/popen.
     *
     * The connection will not be closed on end of request until the php process ends. So be prepared for too many
     * open FD's errors (specially on redis server side) when using persistent connections on many servers
     * connecting to one redis server.
     *
     * Also more than one persistent connection can be made identified by either host + port + timeout or
     * host + persistent_id or unix socket + timeout.
     *
     * Starting from version 4.2.1, it became possible to use connection pooling by setting INI variable
     * redis.pconnect.pooling_enabled to 1.
     *
     * This feature is not available in threaded versions. pconnect and popen then working like their non
     * persistent equivalents.
     *
     * @param  string       $host           can be a host, or the path to a unix domain socket. Starting from version
     *                                      5.0.0 it is possible to specify
     * @param  int|int      $port           optional defaults to 6379
     * @param  float|int    $timeout        value in seconds (optional, default is 0 meaning unlimited)
     * @param  string       $persistent_id  identity for the requested persistent connection
     * @param  int|int      $retry_interval value in milliseconds (optional)
     * @param  float|int    $read_timeout   value in seconds (optional, default is 0 meaning unlimited)
     *
     * @return bool     true on success, false on error
     *
     * @throws RedisException
     */
    public function pconnect(
        string $host,
        int $port,
        float $timeout,
        string $persistent_id,
        ?int $retry_interval = 0,
        ?float $read_timeout = 0
    ) : bool {
        return $this->redis->pconnect($host, $port, $timeout, $persistent_id, $retry_interval, $read_timeout);
    }

    /**
     * Connects to a Redis instance or reuse a connection already established with pconnect/popen.
     *
     * The connection will not be closed on end of request until the php process ends. So be prepared for too many
     * open FD's errors (specially on redis server side) when using persistent connections on many servers
     * connecting to one redis server.
     *
     * Also more than one persistent connection can be made identified by either host + port + timeout or
     * host + persistent_id or unix socket + timeout.
     *
     * Starting from version 4.2.1, it became possible to use connection pooling by setting INI variable
     * redis.pconnect.pooling_enabled to 1.
     *
     * This feature is not available in threaded versions. pconnect and popen then working like their non
     * persistent equivalents.
     *
     * @param  string       $host           can be a host, or the path to a unix domain socket. Starting from version
     *                                      5.0.0 it is possible to specify
     * @param  int|int      $port           optional defaults to 6379
     * @param  float|int    $timeout        value in seconds (optional, default is 0 meaning unlimited)
     * @param  string       $persistent_id  identity for the requested persistent connection
     * @param  int|int      $retry_interval value in milliseconds (optional)
     * @param  float|int    $read_timeout   value in seconds (optional, default is 0 meaning unlimited)
     *
     * @return bool     true on success, false on error
     *
     * @throws RedisException
     */
    public function popen(
        string $host,
        int $port,
        float $timeout,
        string $persistent_id,
        ?int $retry_interval = 0,
        ?float $read_timeout = 0
    ) : bool {
        return $this->pconnect($host, $port, $timeout, $persistent_id, $retry_interval, $read_timeout);
    }

    /**
     * Authenticate the connection using a password. Warning: The password is sent in plain-text over the network.
     *
     * @param  string $password
     *
     * @return bool     TRUE if the connection is authenticated, FALSE otherwise.
     */
    public function auth(string $password) : bool
    {
        return $this->redis->auth($password);
    }

    /**
     * Change the selected database for the current connection.
     *
     * @param  int    $db   the database number to switch to (0 - 15).
     *
     * @return bool     TRUE in case of success, FALSE in case of failure.
     */
    public function select(int $db) : bool
    {
        return $this->redis->select($db);
    }

    /**
     * Swap one Redis database with another atomically.
     *
     * @param  int    $db1
     * @param  int    $db2
     *
     * @return bool     TRUE in case of success, FALSE in case of failure.
     */
    public function swapdb(int $db1, int $db2) : bool
    {
        return $this->redis->swapdb($db1, $db2);
    }

    /**
     * Disconnects from the Redis instance.
     *
     * Note: Closing a persistent connection requires PhpRedis >= 4.2.0.
     *
     * @return bool     TRUE on success, FALSE on failure.
     */
    public function close() : bool
    {
        return $this->redis->close();
    }

    /**
     * Set client option.
     *
     * @param string $name
     * @param string $value
     *
     * @return bool     TRUE on success, FALSE on error.
     */
    public function setOption(string $name, string $value) : bool
    {
        return $this->redis->setOption($name, $value);
    }

    /**
     * Get client option.
     *
     * @param  string $name
     * @return mixed|string|int
     */
    public function getOption(string $name)
    {
        return $this->redis->getOption($name);
    }

    /**
     * Check the current connection status.
     *
     * Note: Prior to PhpRedis 5.0.0 this command simply returned the string +PONG.
     *
     * @param  string|null $message
     *
     * @return Mixed: This method returns TRUE on success, or the passed string if called with an argument.
     */
    public function ping(?string $message = null)
    {
        return $message ? $this->redis->ping($message) : $this->redis->ping();
    }

    /**
     * Sends a string to Redis, which replies with the same string.
     *
     * @param  string $message The message to send.
     *
     * @return string the same message.
     */
    public function echo(string $message) : string
    {
        return $this->redis->echo($message);
    }
}
