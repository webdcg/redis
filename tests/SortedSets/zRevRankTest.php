<?php

namespace Webdcg\Redis\Tests;

use PHPUnit\Framework\TestCase;
use Webdcg\Redis\Redis;

class zRevRankTest extends TestCase
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
        $this->key = 'SortedSets:zRevRank';
        $this->keyOptional = 'SortedSets:zRevRank:Optional';
    }

    /*
     * ========================================================================
     * zRevRank
     *
     * Redis | Sorted Sets | zRevRank => Returns the rank of a given member in the specified sorted set, starting at 0 for the item with the smallest score. zRevRank starts at 0 for the item with the largest score.
     * ========================================================================
     */

    /** @test */
    public function redis_sorted_sets_zRevRank_float_member_int_score()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $total = random_int(1, 10);
        $data = [];
        for ($i = 0; $i < $total; $i++) {
            $member = ($i * 1.1) + 65;
            $value = 1 * $i;
            $data[(string) $member] = $value;
            $this->assertEquals(1, $this->redis->zAdd($this->key, $value, $member));
        }
        // T E S T  -----------------------------------------------------------
        $member = random_int(1, $total);
        $member = array_keys($data)[$member - 1];
        $rank = $this->redis->zRevRank($this->key, $member);
        $this->assertIsInt($rank);
        $this->assertEquals($total - $data[$member] - 1, $rank);
        // Remove all the keys used
        $this->assertEquals(1, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_sorted_sets_zRevRank_int_member_int_score()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $total = random_int(1, 10);
        $data = [];
        for ($i = 0; $i < $total; $i++) {
            $member = $i + 65;
            $value = 1 * $i;
            $data[$member] = $value;
            $this->assertEquals(1, $this->redis->zAdd($this->key, $value, $member));
        }
        // T E S T  -----------------------------------------------------------
        $member = random_int(1, $total);
        $member = array_keys($data)[$member - 1];
        $rank = $this->redis->zRevRank($this->key, $member);
        $this->assertIsInt($rank);
        $this->assertEquals($total - $data[$member] - 1, $rank);
        // Remove all the keys used
        $this->assertEquals(1, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_sorted_sets_zRevRank_string_member_float_score()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $total = random_int(1, 10);
        $data = [];
        for ($i = 0; $i < $total; $i++) {
            $member = chr($i + 65);
            $value = 1.1 * $i;
            $data[$member] = $value;
            $this->assertEquals(1, $this->redis->zAdd($this->key, $value, $member));
        }
        // T E S T  -----------------------------------------------------------
        $member = random_int(1, $total);
        $member = array_keys($data)[$member - 1];
        $rank = $this->redis->zRevRank($this->key, $member);
        $this->assertIsInt($rank);
        $this->assertEquals($total - (int) floor($data[$member]) - 1, $rank);
        // Remove all the keys used
        $this->assertEquals(1, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_sorted_sets_zRevRank_string_member_int_score()
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
        $rank = $this->redis->zRevRank($this->key, $member);
        $this->assertIsInt($rank);
        $this->assertEquals($total - $data[$member] - 1, $rank);
        // Remove all the keys used
        $this->assertEquals(1, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_sorted_sets_zRevRank_top_member()
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
        $member = $total;
        $member = array_keys($data)[$member - 1];
        $rank = $this->redis->zRevRank($this->key, $member);
        $this->assertIsInt($rank);
        $this->assertEquals(0, $rank);
        // Remove all the keys used
        $this->assertEquals(1, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_sorted_sets_zRevRank_bottom_member()
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
        $member = 1;
        $member = array_keys($data)[$member - 1];
        $rank = $this->redis->zRevRank($this->key, $member);
        $this->assertIsInt($rank);
        $this->assertEquals($total - $data[$member] - 1, $rank);
        // Remove all the keys used
        $this->assertEquals(1, $this->redis->delete($this->key));
    }
}
