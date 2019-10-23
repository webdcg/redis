<?php

namespace Webdcg\Redis\Traits;

trait Connection
{
    public function connect(string $host = '127.0.0.1', int $port = 6379, float $timeout = 0, $reserved = null, int $retry_interval = 0, float $read_timeout = 0) : bool
    {
        return $this->redis->connect($host, $port, $timeout, $reserved, $retry_interval, $read_timeout);
    }

    public function open(string $host = '127.0.0.1', int $port = 6379, float $timeout = 0, $reserved = null, int $retry_interval = 0, float $read_timeout = 0) : bool
    {
        return $this->connect($host, $port, $timeout, $reserved, $retry_interval, $read_timeout);
    }
}
