<?php

namespace Webdcg\Redis\Tests;

use PHPUnit\Framework\TestCase;
use Webdcg\Redis\Redis;

class zRemoveTest extends TestCase
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
        $this->key = 'SortedSets:zRemove';
        $this->keyOptional = 'SortedSets:zRemove:Optional';
    }

    /*
     * ========================================================================
     * zRemove
     *
     * Redis | Sorted Sets | zRemove => Delete one or more members from a sorted set.
     * Note: zRemoveRangeByRank is an alias for zRemRangeByRank and will be removed in future versions of phpredis.
     * ========================================================================
     */


    /** @test */
    public function redis_sorted_sets_zRemove_all_members()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $total = random_int(5, 10);
        $data = [];
        for ($i = 0; $i < $total; $i++) {
            $member = chr($i + 65);
            $value = 1 * $i;
            $data[$member] = $value;
            $this->assertEquals(1, $this->redis->zAdd($this->key, $value, $member));
        }
        // T E S T  -----------------------------------------------------------
        $removed = $this->redis->zRemove($this->key, ...array_keys($data));
        $this->assertIsInt($removed);
        $this->assertEquals($total, $removed);
        $this->assertEquals(0, $this->redis->zCard($this->key));
        $range = $this->redis->zRange($this->key);
        $this->assertIsArray($range);
        $this->assertEquals([], $range);
        // Remove all the keys used
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_sorted_sets_zRemove_multiple_members()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $total = random_int(5, 10);
        $data = [];
        for ($i = 0; $i < $total; $i++) {
            $member = chr($i + 65);
            $value = 1 * $i;
            $data[$member] = $value;
            $this->assertEquals(1, $this->redis->zAdd($this->key, $value, $member));
        }
        // T E S T  -----------------------------------------------------------
        $member = random_int(1, $total / 2);
        $member = array_keys($data)[$member - 1];
        $member2 = random_int($total / 2 + 1, $total);
        $member2 = array_keys($data)[$member2 - 1];
        $removed = $this->redis->zRemove($this->key, $member, $member2);
        $this->assertIsInt($removed);
        $this->assertEquals(2, $removed);
        $this->assertEquals($total - 2, $this->redis->zCard($this->key));
        $range = $this->redis->zRange($this->key);
        $this->assertIsArray($range);
        $this->assertNotContains($member, $range);
        $this->assertNotContains($member2, $range);
        // Remove all the keys used
        $this->assertEquals(1, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_sorted_sets_zRemove_single_member()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $total = random_int(2, 10);
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
        $removed = $this->redis->zRemove($this->key, $member);
        $this->assertIsInt($removed);
        $this->assertEquals(1, $removed);
        $this->assertEquals($total - 1, $this->redis->zCard($this->key));
        $range = $this->redis->zRange($this->key);
        $this->assertIsArray($range);
        $this->assertNotContains($member, $range);
        // Remove all the keys used
        $this->assertEquals(1, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_sorted_sets_zRemove_nonexisting_member()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $total = random_int(2, 10);
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
        $member = 'Z';
        $removed = $this->redis->zRemove($this->key, $member);
        $this->assertIsInt($removed);
        $this->assertEquals(0, $removed);
        $this->assertEquals($total, $this->redis->zCard($this->key));
        $range = $this->redis->zRange($this->key);
        $this->assertIsArray($range);
        $this->assertNotContains($member, $range);
        // Remove all the keys used
        $this->assertEquals(1, $this->redis->delete($this->key));
    }
}
