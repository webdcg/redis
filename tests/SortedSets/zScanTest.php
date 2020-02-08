<?php

namespace Webdcg\Redis\Tests;

use PHPUnit\Framework\TestCase;
use Webdcg\Redis\Redis;

class zScanTest extends TestCase
{
    protected $redis;
    protected $key;
    protected $keyOptional;
    protected $producer;

    protected function setUp(): void
    {
        $this->redis = new Redis();
        $this->redis->connect();
        $this->redis->setOption(\Redis::OPT_SERIALIZER, \Redis::SERIALIZER_NONE);
        $this->key = 'SortedSets:zScan';
        $this->keyOptional = 'SortedSets:zScan:Optional';
    }


    /*
     * ========================================================================
     * zScan
     *
     * Redis | Sorted Sets | zScan => Scan a sorted set for members, with optional pattern and count.
     * ========================================================================
     */


    /** @test */
    public function redis_keys_zScan_defaults()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));

        $total = random_int(1, 10);
        $data = [];
        for ($i = 0; $i < $total; $i++) {
            $member = ($i * 1.1) + 65;
            $value = 1.1 * $i;
            $data[(string) $member] = $value;
            $this->assertEquals(1, $this->redis->zAdd($this->key, $value, $member));
        }

        /* Without enabling Redis::SCAN_RETRY (default condition) */
        $it = null;
        do {
            // Scan for some keys
            // --------------------  T E S T  --------------------
            $members = $this->redis->zScan($this->key, $it);

            // Redis may return empty results, so protect against that
            if ($members !== false) {
                $this->assertContains($this->key, $members);
            }
        } while ($it > 0);

        // Cleanup used keys
        $this->assertEquals(1, $this->redis->delete($this->key));
    }
}
