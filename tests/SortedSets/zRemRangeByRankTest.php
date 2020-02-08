<?php

namespace Webdcg\Redis\Tests;

use PHPUnit\Framework\TestCase;
use Webdcg\Redis\Exceptions\InvalidArgumentException;
use Webdcg\Redis\Redis;

class zRemRangeByRankTest extends TestCase
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
        $this->key = 'SortedSets:zRemRangeByRank';
        $this->keyOptional = 'SortedSets:zRemRangeByRank:Optional';
    }


    /*
     * ========================================================================
     * zRemRangeByRank
     *
     * Redis | Sorted Sets | zRemRangeByRank => Deletes the elements of the sorted set stored at the specified key which have rank in the range [start,end].
     * Note: zDeleteRangeByRank is an alias for zRemRangeByRank and will be removed in future versions of phpredis.
     * ========================================================================
     */


    /** @test */
    public function redis_sorted_sets_zRemRangeByRank_inner_members()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $total = random_int(3, 10);
        $data = [];
        for ($i = 0; $i < $total; $i++) {
            $member = chr($i + 65);
            $value = 1 * $i;
            $data[$member] = $value;
            $this->assertEquals(1, $this->redis->zAdd($this->key, $value, $member));
        }
        $member = random_int(1, $total - 2);
        $member = array_keys($data)[$member];
        $expected = $total - 2;
        // T E S T  -----------------------------------------------------------
        $removed = $this->redis->zRemRangeByRank($this->key, 1, $total - 2);
        $range = $this->redis->zRange($this->key);
        // T E S T  -----------------------------------------------------------
        $this->assertIsInt($removed);
        $this->assertEquals($expected, $removed);
        $this->assertEquals($total - $removed, $this->redis->zCard($this->key));
        $this->assertIsArray($range);
        $this->assertNotContains($member, $range);
        // Remove all the keys used
        $this->assertEquals(1, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_sorted_sets_zRemRangeByRank_top_half()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $total = random_int(3, 10);
        $data = [];
        for ($i = 0; $i < $total; $i++) {
            $member = chr($i + 65);
            $value = 1 * $i;
            $data[$member] = $value;
            $this->assertEquals(1, $this->redis->zAdd($this->key, $value, $member));
        }
        $member = random_int(ceil($total / 2), $total - 1);
        $member = array_keys($data)[$member];
        $expected = (int) floor($total / 2);
        // T E S T  -----------------------------------------------------------
        $removed = $this->redis->zRemRangeByRank($this->key, $expected, $total);
        $range = $this->redis->zRange($this->key);
        // T E S T  -----------------------------------------------------------
        $expected = $total % 2 == 0 ? $expected : $expected + 1;
        $this->assertIsInt($removed);
        $this->assertEquals($expected, $removed);
        $this->assertEquals($total - $removed, $this->redis->zCard($this->key));
        $this->assertIsArray($range);
        $this->assertNotContains($member, $range);
        // Remove all the keys used
        $this->assertEquals(1, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_sorted_sets_zRemRangeByRank_bottom_half()
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
        $member = random_int(1, $total / 2);
        $member = array_keys($data)[$member - 1];
        $expected = (int) floor($total / 2);
        // T E S T  -----------------------------------------------------------
        $removed = $this->redis->zRemRangeByRank($this->key, 0, $expected);
        $range = $this->redis->zRange($this->key);
        // T E S T  -----------------------------------------------------------
        $this->assertIsInt($removed);
        $this->assertEquals($expected + 1, $removed);
        $this->assertEquals($total - $removed, $this->redis->zCard($this->key));
        $this->assertIsArray($range);
        $this->assertNotContains($member, $range);
        // Remove all the keys used
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_sorted_sets_zRemRangeByRank_invalid_arguments()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $total = random_int(2, 10);
        $this->expectException(InvalidArgumentException::class);
        $this->redis->zRemRangeByRank($this->key, $total, 0);
    }

    /** @test */
    public function redis_sorted_sets_zRemRangeByRank_out_of_bounds()
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
        $member = 'Z';
        // T E S T  -----------------------------------------------------------
        $removed = $this->redis->zRemRangeByRank($this->key, $total + 1, $total + 2);
        // T E S T  -----------------------------------------------------------
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
