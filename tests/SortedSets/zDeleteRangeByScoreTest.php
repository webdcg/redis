<?php

namespace Webdcg\Redis\Tests;

use PHPUnit\Framework\TestCase;
use Webdcg\Redis\Exceptions\InvalidArgumentException;
use Webdcg\Redis\Redis;

class zDeleteRangeByScoreTest extends TestCase
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
        $this->key = 'SortedSets:zDeleteRangeByScore';
        $this->keyOptional = 'SortedSets:zDeleteRangeByScore:Optional';
    }


    /*
     * ========================================================================
     * zDeleteRangeByScore
     *
     * Redis | Sorted Sets | zDeleteRangeByScore => Deletes the elements of the sorted set stored at the specified key which have scores in the range [start,end].
     * Note: zDeleteRangeByScore and zRemoveRangeByScore are an alias for zDeleteRangeByScore and will be removed in future versions of phpredis.
     * ========================================================================
     */


    /** @test */
    public function redis_sorted_sets_zDeleteRangeByScore_inner_members()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $total = random_int(4, 10);
        $data = [];
        for ($i = 0; $i < $total; $i++) {
            $member = chr($i + 65);
            $value = 1.1 * $i;
            $data[$member] = $value;
            $this->assertEquals(1, $this->redis->zAdd($this->key, $value, $member));
        }
        $start = 1;
        $start = array_keys($data)[$start];
        $expected = $total - 2;
        $end = array_values($data)[$total - 2] + 0.1;
        // T E S T  -----------------------------------------------------------
        $removed = $this->redis->zDeleteRangeByScore($this->key, $data[$start], $end);
        $range = $this->redis->zRange($this->key);
        // T E S T  -----------------------------------------------------------
        $this->assertIsInt($removed);
        $this->assertEquals($expected, $removed);
        $this->assertEquals($total - $removed, $this->redis->zCard($this->key));
        $this->assertIsArray($range);
        $this->assertContains($member, $range);
        // Remove all the keys used
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_sorted_sets_zDeleteRangeByScore_top_half()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $total = random_int(4, 10);
        $data = [];
        for ($i = 0; $i < $total; $i++) {
            $member = chr($i + 65);
            $value = 1.1 * $i;
            $data[$member] = $value;
            $this->assertEquals(1, $this->redis->zAdd($this->key, $value, $member));
        }
        $start = $total / 2;
        $start = array_keys($data)[$start];
        $expected = (int) floor($data[$start]);
        $end = array_values($data)[$total - 1] + 0.1;
        $expected = $total % 2 == 0 ? $expected : $expected + 1;
        // T E S T  -----------------------------------------------------------
        $removed = $this->redis->zDeleteRangeByScore($this->key, $data[$start], $end);
        $range = $this->redis->zRange($this->key);
        // T E S T  -----------------------------------------------------------
        $this->assertIsInt($removed);
        $this->assertEquals($expected, $removed);
        $this->assertEquals($total - $removed, $this->redis->zCard($this->key));
        $this->assertIsArray($range);
        $this->assertNotContains($member, $range);
        // Remove all the keys used
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_sorted_sets_zDeleteRangeByScore_bottom_half()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $total = random_int(4, 10);
        $data = [];
        for ($i = 0; $i < $total; $i++) {
            $member = chr($i + 65);
            $value = 1.1 * $i;
            $data[$member] = $value;
            $this->assertEquals(1, $this->redis->zAdd($this->key, $value, $member));
        }
        $member = $total / 2;
        $member = array_keys($data)[$member];
        $expected = (int) floor($data[$member]) + 1;
        // T E S T  -----------------------------------------------------------
        $removed = $this->redis->zDeleteRangeByScore($this->key, 0.0, $data[$member]);
        $range = $this->redis->zRange($this->key);
        // T E S T  -----------------------------------------------------------
        $this->assertIsInt($removed);
        $this->assertEquals($expected, $removed);
        $this->assertEquals($total - $removed, $this->redis->zCard($this->key));
        $this->assertIsArray($range);
        $this->assertNotContains($member, $range);
        // Remove all the keys used
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_sorted_sets_zDeleteRangeByScore_invalid_range()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $total = random_int(2, 10);
        $this->expectException(InvalidArgumentException::class);
        $this->redis->zDeleteRangeByScore($this->key, $total * 1.1, 0.1);
    }

    /** @test */
    public function redis_sorted_sets_zDeleteRangeByScore_invalid_end_type()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $this->expectException(InvalidArgumentException::class);
        $this->redis->zDeleteRangeByScore($this->key, '-inf', 1);
    }

    /** @test */
    public function redis_sorted_sets_zDeleteRangeByScore_invalid_start_type()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $this->expectException(InvalidArgumentException::class);
        $this->redis->zDeleteRangeByScore($this->key, 0, 1.1);
    }

    /** @test */
    public function redis_sorted_sets_zDeleteRangeByScore_out_of_bounds()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $total = random_int(2, 10);
        $data = [];
        for ($i = 0; $i < $total; $i++) {
            $member = chr($i + 65);
            $value = 1.1 * $i;
            $data[$member] = $value;
            $this->assertEquals(1, $this->redis->zAdd($this->key, $value, $member));
        }
        $member = 'Z';
        // T E S T  -----------------------------------------------------------
        $removed = $this->redis->zDeleteRangeByScore($this->key, $total + 99.9, $total + 199.9);
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
