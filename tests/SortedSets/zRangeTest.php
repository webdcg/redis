<?php

namespace Webdcg\Redis\Tests;

use PHPUnit\Framework\TestCase;
use Webdcg\Redis\Redis;

class zRangeTest extends TestCase
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
        $this->key = 'SortedSets:zRange';
        $this->keyOptional = 'SortedSets:zRange:Optional';
    }

    /*
     * ========================================================================
     * zRange
     * ========================================================================
     */

    /** @test */
    public function redis_sorted_sets_zrange_with_scores()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $this->assertEquals(1, $this->redis->zAdd($this->key, 1.1, 'A'));
        $this->assertEquals(1, $this->redis->zAdd($this->key, 2.2, 'B'));
        $this->assertEquals(1, $this->redis->zAdd($this->key, 3.3, 'C'));
        $this->assertEquals(1, $this->redis->zAdd($this->key, 3.3, 'D'));
        $this->assertEquals(4, $this->redis->zCard($this->key));
        // T E S T  -----------------------------------------------------------
        $range = $this->redis->zRange($this->key, 1, 2, true);
        $this->assertIsArray($range);
        $this->assertEquals(2, count($range));
        $this->assertArraySubset(['B' => 2.2], $range);
        $this->assertArraySubset(['C' => 3.3], $range);
        $this->assertArrayNotHasKey('A', $range);
        // Remove all the keys used
        $this->assertEquals(1, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_sorted_sets_zrange_start_end()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $this->assertEquals(1, $this->redis->zAdd($this->key, 1.1, 'A'));
        $this->assertEquals(1, $this->redis->zAdd($this->key, 2.2, 'B'));
        $this->assertEquals(1, $this->redis->zAdd($this->key, 3.3, 'C'));
        $this->assertEquals(1, $this->redis->zAdd($this->key, 3.3, 'D'));
        $this->assertEquals(4, $this->redis->zCard($this->key));
        // T E S T  -----------------------------------------------------------
        $range = $this->redis->zRange($this->key, 1, 2);
        $this->assertIsArray($range);
        $this->assertEquals(2, count($range));
        $this->assertContains('B', $range);
        $this->assertContains('C', $range);
        $this->assertNotContains('A', $range);
        // Remove all the keys used
        $this->assertEquals(1, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_sorted_sets_zrange_start()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $this->assertEquals(1, $this->redis->zAdd($this->key, 1.1, 'A'));
        $this->assertEquals(1, $this->redis->zAdd($this->key, 2.2, 'B'));
        $this->assertEquals(1, $this->redis->zAdd($this->key, 3.3, 'C'));
        $this->assertEquals(3, $this->redis->zCard($this->key));
        // T E S T  -----------------------------------------------------------
        $range = $this->redis->zRange($this->key, 1);
        $this->assertIsArray($range);
        $this->assertEquals(2, count($range));
        $this->assertContains('B', $range);
        $this->assertContains('C', $range);
        $this->assertNotContains('A', $range);
        // Remove all the keys used
        $this->assertEquals(1, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_sorted_sets_zrange_defaults()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $this->assertEquals(1, $this->redis->zAdd($this->key, 1.1, 'A'));
        $this->assertEquals(1, $this->redis->zAdd($this->key, 2.2, 'B'));
        $this->assertEquals(1, $this->redis->zAdd($this->key, 3.3, 'C'));
        $this->assertEquals(3, $this->redis->zCard($this->key));
        // T E S T  -----------------------------------------------------------
        $range = $this->redis->zRange($this->key);
        $this->assertIsArray($range);
        $this->assertEquals(3, count($range));
        $this->assertContains('A', $range);
        $this->assertContains('B', $range);
        $this->assertContains('C', $range);
        $this->assertNotContains('D', $range);
        // Remove all the keys used
        $this->assertEquals(1, $this->redis->delete($this->key));
    }
}
