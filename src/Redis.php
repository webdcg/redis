<?php

namespace Webdcg\Redis;

use Webdcg\Redis\Traits\Connection;

class Redis
{
    use Connection;

    protected $redis;

    public function __construct()
    {
        $this->redis = new \Redis();
    }
}
