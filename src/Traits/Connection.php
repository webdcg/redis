<?php

namespace Webdcg\Redis\Traits;

trait Connection
{
    /**
     * Connects to a Redis instance.
     *
     * @param  string        $host           can be a host, or the path to a unix domain socket. Starting from version 5.0.0 it is possible to specify
     * @param  int|int   $port           optional defaults to 6379
     * @param  float|int $timeout        value in seconds (optional, default is 0 meaning unlimited)
     * @param  null         $reserved       should be NULL if retry_interval is specified
     * @param  int|int   $retry_interval value in milliseconds (optional)
     * @param  float|int $read_timeout   value in seconds (optional, default is 0 meaning unlimited)
     *
     * @return bool                         true on success, false on error
     *
     * @throws RedisException
     */
    public function connect(string $host = '127.0.0.1', int $port = 6379, float $timeout = 0, $reserved = null, int $retry_interval = 0, float $read_timeout = 0) : bool
    {
        return $this->redis->connect($host, $port, $timeout, $reserved, $retry_interval, $read_timeout);
    }

    /**
     * Connects to a Redis instance.
     *
     * @param  string        $host           can be a host, or the path to a unix domain socket. Starting from version 5.0.0 it is possible to specify
     * @param  int|int   $port           optional
     * @param  float|int $timeout        value in seconds (optional, default is 0 meaning unlimited)
     * @param  null         $reserved       should be NULL if retry_interval is specified
     * @param  int|int   $retry_interval value in milliseconds (optional)
     * @param  float|int $read_timeout   value in seconds (optional, default is 0 meaning unlimited)
     *
     * @return bool                         true on success, false on error
     *
     * @throws RedisException
     */
    public function open(string $host = '127.0.0.1', int $port = 6379, float $timeout = 0, $reserved = null, int $retry_interval = 0, float $read_timeout = 0) : bool
    {
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
     * @param  string        $host           can be a host, or the path to a unix domain socket. Starting from version 5.0.0 it is possible to specify
     * @param  int|int   $port           optional defaults to 6379
     * @param  float|int $timeout        value in seconds (optional, default is 0 meaning unlimited)
     * @param  string        $persistent_id  identity for the requested persistent connection
     * @param  int|int   $retry_interval value in milliseconds (optional)
     * @param  float|int $read_timeout   value in seconds (optional, default is 0 meaning unlimited)
     *
     * @return bool                         true on success, false on error
     *
     * @throws RedisException
     */
    public function pconnect(string $host, int $port, float $timeout, string $persistent_id, ?int $retry_interval = 0, ?float $read_timeout = 0) : bool
    {
        return $this->redis->pconnect($host, $port, $timeout, $persistent_id, $retry_interval, $read_timeout);
    }

    /**
     * Connects to a Redis instance or reuse a connection already established with pconnect/popen.
     *
     * @param  string        $host           can be a host, or the path to a unix domain socket. Starting from version 5.0.0 it is possible to specify
     * @param  int|int   $port           optional defaults to 6379
     * @param  float|int $timeout        value in seconds (optional, default is 0 meaning unlimited)
     * @param  string        $persistent_id  identity for the requested persistent connection
     * @param  int|int   $retry_interval value in milliseconds (optional)
     * @param  float|int $read_timeout   value in seconds (optional, default is 0 meaning unlimited)
     *
     * @return bool                         true on success, false on error
     *
     * @throws RedisException
     */
    public function popen(string $host, int $port, float $timeout, string $persistent_id, ?int $retry_interval = 0, ?float $read_timeout = 0) : bool
    {
        return $this->pconnect($host, $port, $timeout, $persistent_id, $retry_interval, $read_timeout);
    }

    /**
     * Authenticate the connection using a password. Warning: The password is sent in plain-text over the network.
     *
     * @param  string $password
     *
     * @return bool             TRUE if the connection is authenticated, FALSE otherwise.
     */
    public function auth(string $password) : bool
    {
        return $this->redis->auth($password);
    }
}
