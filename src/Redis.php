<?php

namespace Webdcg\Redis;

use Webdcg\Redis\Traits\Strings;
use Webdcg\Redis\Traits\Connection;

class Redis
{
    use Connection;
    use Strings;

    protected $redis;

    public function __construct()
    {
        $this->redis = new \Redis();
    }
}
