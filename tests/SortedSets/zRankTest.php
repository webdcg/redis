<?php

namespace Webdcg\Redis\Tests;

use PHPUnit\Framework\TestCase;
use Webdcg\Redis\Redis;

class zRankTest extends TestCase
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
        $this->key = 'SortedSets:zRank';
        $this->keyOptional = 'SortedSets:zRank:Optional';
    }

    /*
     * ========================================================================
     * zRank
     *
     * Redis | Sorted Sets | zRank => Returns the rank of a given member in the specified sorted set, starting at 0 for the item with the smallest score.
     * ========================================================================
     */

    /** @test */
    public function redis_sorted_sets_zRank_string_member_int_score()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $total = random_int(1, 10);
        $data = [];
        for ($i = 0; $i < $total; $i++) {
            $member = chr($i + 65);
            $value = 1 * $i;
            $data[$member] = $value;
            $this->assertEquals(1, $this->redis->zAdd($this->key, $value, $member));
        }
        // T E S T  -----------------------------------------------------------
        $member = random_int(1, $total);
        $member = array_keys($data)[$member - 1];
        $rank = $this->redis->zRank($this->key, $member);
        $this->assertEquals($data[$member], $rank);
        // Remove all the keys used
        $this->assertEquals(1, $this->redis->delete($this->key));
    }
}
