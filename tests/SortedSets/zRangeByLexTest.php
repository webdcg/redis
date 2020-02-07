<?php

namespace Webdcg\Redis\Tests;

use PHPUnit\Framework\TestCase;
use Webdcg\Redis\Exceptions\InvalidArgumentException;
use Webdcg\Redis\Redis;

class zRangeByLexTest extends TestCase
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
        $this->key = 'SortedSets:zRangeByLex';
        $this->keyOptional = 'SortedSets:zRangeByLex:Optional';
    }

    /*
     * ========================================================================
     * zRangeByLex
     *
     * Redis | Sorted Sets | zRangeByLex => Returns a lexicographical range of members in a sorted set, assuming the members have the same score.
     * ========================================================================
     */
    
    /** @test */
    public function redis_sorted_sets_zRangeByLex_limit_offset()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $total = 10;
        $data = [];
        for ($i = 0; $i < $total; $i++) {
            $member = chr($i + 65);
            $value = 1.1;
            $data[$member] = $value;
            $this->assertEquals(1, $this->redis->zAdd($this->key, $value, $member));
        }
        // T E S T  -----------------------------------------------------------
        $expected = array_keys($data);
        $range = $this->redis->zRangeByLex($this->key, '-', '[E', 2, 2);
        
        $this->assertIsArray($range);
        $this->assertEquals(2, count($range));
        
        for ($i = 2; $i < 4; $i++) {
            $this->assertContains($expected[$i], $range);
        }

        $this->assertEquals(array_slice($expected, 2, 2), $range);
        // Remove all the keys used
        $this->assertEquals(1, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_sorted_sets_zRangeByLex_top_half()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $total = 10;
        $data = [];
        for ($i = 0; $i < $total; $i++) {
            $member = chr($i + 65);
            $value = 1.1;
            $data[$member] = $value;
            $this->assertEquals(1, $this->redis->zAdd($this->key, $value, $member));
        }
        // T E S T  -----------------------------------------------------------
        $expected = array_keys($data);
        $range = $this->redis->zRangeByLex($this->key, '(E', '+');

        $this->assertIsArray($range);
        $this->assertEquals($total / 2, count($range));
        
        for ($i = 5; $i < $total; $i++) {
            $this->assertContains($expected[$i], $range);
        }

        $this->assertEquals(array_slice($expected, 5, 5), $range);
        // Remove all the keys used
        $this->assertEquals(1, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_sorted_sets_zRangeByLex_bottom_half()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $total = 10;
        $data = [];
        for ($i = 0; $i < $total; $i++) {
            $member = chr($i + 65);
            $value = 1.1;
            $data[$member] = $value;
            $this->assertEquals(1, $this->redis->zAdd($this->key, $value, $member));
        }
        // T E S T  -----------------------------------------------------------
        $expected = array_keys($data);
        $range = $this->redis->zRangeByLex($this->key, '-', '[E');
        
        $this->assertIsArray($range);
        $this->assertEquals($total / 2, count($range));
        
        for ($i = 0; $i < $total / 2; $i++) {
            $this->assertContains($expected[$i], $range);
        }

        $this->assertEquals(array_slice($expected, 0, 5), $range);
        // Remove all the keys used
        $this->assertEquals(1, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_sorted_sets_zRangeByLex_all_members()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $total = 10;
        $data = [];
        for ($i = 0; $i < $total; $i++) {
            $member = chr($i + 65);
            $value = 1.1;
            $data[$member] = $value;
            $this->assertEquals(1, $this->redis->zAdd($this->key, $value, $member));
        }
        // T E S T  -----------------------------------------------------------
        $expected = array_keys($data);
        $range = $this->redis->zRangeByLex($this->key, '-', '+');

        $this->assertIsArray($range);
        $this->assertEquals($total, count($range));

        foreach ($expected as $key) {
            $this->assertContains($key, $range);
        }

        $this->assertEquals($expected, $range);
        // Remove all the keys used
        $this->assertEquals(1, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_sorted_sets_zRangeByLex_inclusive_parameters()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        // T E S T  -----------------------------------------------------------
        $range = $this->redis->zRangeByLex($this->key, '[A', '[Z');
        $this->assertIsArray($range);
        $this->assertEquals([], $range);
    }

    /** @test */
    public function redis_sorted_sets_zRangeByLex_exclusive_parameters()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        // T E S T  -----------------------------------------------------------
        $range = $this->redis->zRangeByLex($this->key, '(A', '(Z');
        $this->assertIsArray($range);
        $this->assertEquals([], $range);
    }

    /** @test */
    public function redis_sorted_sets_zRangeByLex_infinite_parameters()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        // T E S T  -----------------------------------------------------------
        $range = $this->redis->zRangeByLex($this->key, '-', '+');
        $this->assertIsArray($range);
        $this->assertEquals([], $range);
    }

    /** @test */
    public function redis_sorted_sets_zRangeByLex_max_invalid_parameter()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        // T E S T  -----------------------------------------------------------
        $this->expectException(InvalidArgumentException::class);
        $this->redis->zRangeByLex($this->key, '[A', '{B');
    }

    /** @test */
    public function redis_sorted_sets_zRangeByLex_min_invalid_parameter()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        // T E S T  -----------------------------------------------------------
        $this->expectException(InvalidArgumentException::class);
        $this->redis->zRangeByLex($this->key, 'A', '(B');
    }

    /** @test */
    public function redis_sorted_sets_zRangeByLex_both_invalid_parameters()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        // T E S T  -----------------------------------------------------------
        $this->expectException(InvalidArgumentException::class);
        $this->redis->zRangeByLex($this->key, 'A', '{B');
    }

    /** @test */
    public function redis_sorted_sets_zRangeByLex_wrong_parameter_number()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        // T E S T  -----------------------------------------------------------
        $this->expectException(InvalidArgumentException::class);
        $this->redis->zRangeByLex($this->key, '-', '[C', 2);
    }
}
