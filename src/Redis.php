<?php

namespace Webdcg\Redis;

use Webdcg\Redis\Traits\Bits;
use Webdcg\Redis\Traits\Connection;
use Webdcg\Redis\Traits\Geocoding;
use Webdcg\Redis\Traits\HyperLogLogs;
use Webdcg\Redis\Traits\Keys;
use Webdcg\Redis\Traits\Strings;

class Redis
{
    use Bits;
    use Connection;
    use Geocoding;
    use HyperLogLogs;
    use Keys;
    use Strings;

    protected $redis;

    public function __construct()
    {
        $this->redis = new \Redis();
    }
}
