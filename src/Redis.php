<?php

namespace Webdcg\Redis;

use Webdcg\Redis\Traits\Bits;
use Webdcg\Redis\Traits\Connection;
use Webdcg\Redis\Traits\Geocoding;
use Webdcg\Redis\Traits\Hashes;
use Webdcg\Redis\Traits\HyperLogLogs;
use Webdcg\Redis\Traits\Keys;
use Webdcg\Redis\Traits\Lists;
use Webdcg\Redis\Traits\Pubsub;
use Webdcg\Redis\Traits\Scripting;
use Webdcg\Redis\Traits\Sets;
use Webdcg\Redis\Traits\SortedSets;
use Webdcg\Redis\Traits\Streams;
use Webdcg\Redis\Traits\Strings;
use Webdcg\Redis\Traits\Transactions;

class Redis
{
    use Bits;
    use Connection;
    use Geocoding;
    use Hashes;
    use HyperLogLogs;
    use Lists;
    use Keys;
    use Pubsub;
    use Scripting;
    use Sets;
    use SortedSets;
    use Streams;
    use Strings;
    use Transactions;

    protected $redis;

    public function __construct()
    {
        $this->redis = new \Redis();
    }

    /**
     * Check that the given array is associative.
     *
     * @param  array   $array
     *
     * @return bool
     */
    public function is_associative(array $array)
    {
        return array_keys($array) !== range(0, count($array) - 1);
    }
}
