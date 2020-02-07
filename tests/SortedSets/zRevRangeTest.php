<?php

namespace Webdcg\Redis\Tests;

use PHPUnit\Framework\TestCase;
use Webdcg\Redis\Redis;

class zRevRangeTest extends TestCase
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
        $this->key = 'SortedSets:zRevRange';
        $this->keyOptional = 'SortedSets:zRevRange:Optional';
    }

    /*
     * ========================================================================
     * zRevRange
     *
     * Redis | Sorted Sets | zRevRange => Returns the elements of the sorted set stored at the specified key in the range [start, end] in reverse order.
     * ========================================================================
     */

    /** @test */
    public function redis_sorted_sets_zRevRange_with_scores()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $this->assertEquals(1, $this->redis->zAdd($this->key, 1.1, 'A'));
        $this->assertEquals(1, $this->redis->zAdd($this->key, 2.2, 'B'));
        $this->assertEquals(1, $this->redis->zAdd($this->key, 3.3, 'C'));
        $this->assertEquals(1, $this->redis->zAdd($this->key, 3.3, 'D'));
        $this->assertEquals(4, $this->redis->zCard($this->key));
        // T E S T  -----------------------------------------------------------
        $expected = [
            'C' => 3.3,
            'B' => 2.2,
        ];
        $range = $this->redis->zRevRange($this->key, 1, 2, true);
        $this->assertIsArray($range);
        $this->assertEquals(2, count($range));
        $this->assertEquals($expected, $range);
        $this->assertArraySubset(['B' => 2.2], $range);
        $this->assertArraySubset(['C' => 3.3], $range);
        $this->assertArrayNotHasKey('A', $range);
        // Remove all the keys used
        $this->assertEquals(1, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_sorted_sets_zRevRange_start_end()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $this->assertEquals(1, $this->redis->zAdd($this->key, 1.1, 'A'));
        $this->assertEquals(1, $this->redis->zAdd($this->key, 2.2, 'B'));
        $this->assertEquals(1, $this->redis->zAdd($this->key, 3.3, 'C'));
        $this->assertEquals(1, $this->redis->zAdd($this->key, 3.3, 'D'));
        $this->assertEquals(4, $this->redis->zCard($this->key));
        // T E S T  -----------------------------------------------------------
        $expected = ['C', 'B', ];
        $range = $this->redis->zRevRange($this->key, 1, 2);
        $this->assertIsArray($range);
        $this->assertEquals(2, count($range));
        $this->assertEquals($expected, $range);
        $this->assertContains('B', $range);
        $this->assertContains('C', $range);
        $this->assertNotContains('D', $range);
        // Remove all the keys used
        $this->assertEquals(1, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_sorted_sets_zRevRange_start()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $this->assertEquals(1, $this->redis->zAdd($this->key, 1.1, 'A'));
        $this->assertEquals(1, $this->redis->zAdd($this->key, 2.2, 'B'));
        $this->assertEquals(1, $this->redis->zAdd($this->key, 3.3, 'C'));
        $this->assertEquals(3, $this->redis->zCard($this->key));
        // T E S T  -----------------------------------------------------------
        $expected = ['B', 'A', ];
        $range = $this->redis->zRevRange($this->key, 1);
        $this->assertIsArray($range);
        $this->assertEquals(2, count($range));
        $this->assertEquals($expected, $range);
        $this->assertContains('B', $range);
        $this->assertContains('A', $range);
        $this->assertNotContains('C', $range);
        // Remove all the keys used
        $this->assertEquals(1, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_sorted_sets_zRevRange_defaults()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $this->assertEquals(1, $this->redis->zAdd($this->key, 1.1, 'A'));
        $this->assertEquals(1, $this->redis->zAdd($this->key, 2.2, 'B'));
        $this->assertEquals(1, $this->redis->zAdd($this->key, 3.3, 'C'));
        $this->assertEquals(3, $this->redis->zCard($this->key));
        // T E S T  -----------------------------------------------------------
        $expected = ['C', 'B', 'A', ];
        $range = $this->redis->zRevRange($this->key);
        $this->assertIsArray($range);
        $this->assertEquals(3, count($range));
        $this->assertEquals($expected, $range);
        $this->assertContains('A', $range);
        $this->assertContains('B', $range);
        $this->assertContains('C', $range);
        $this->assertNotContains('D', $range);
        // Remove all the keys used
        $this->assertEquals(1, $this->redis->delete($this->key));
    }
}
