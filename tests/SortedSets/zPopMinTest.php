<?php

namespace Webdcg\Redis\Tests;

use PHPUnit\Framework\TestCase;
use Webdcg\Redis\Redis;

class zPopMinTest extends TestCase
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
        $this->key = 'SortedSets:zPopMin';
        $this->keyOptional = 'SortedSets:zPopMin:Optional';
    }

    /*
     * ========================================================================
     * zPopMin
     * ========================================================================
     */

    /** @test */
    public function redis_sorted_sets_zpop_min()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $this->assertEquals(1, $this->redis->zAdd($this->key, 1.1, 'A'));
        $this->assertEquals(1, $this->redis->zAdd($this->key, 2.2, 'B'));
        $this->assertEquals(1, $this->redis->zAdd($this->key, 3.3, 'C'));
        $this->assertEquals(3, $this->redis->zCard($this->key));
        // T E S T  -----------------------------------------------------------
        $min = $this->redis->zPop($this->key, 2, false);
        $this->assertIsArray($min);
        $this->assertArraySubset(['A' => 1.1], $min);
        $this->assertArraySubset(['B' => 2.2], $min);
        $this->assertEquals(1, $this->redis->zCard($this->key));
        // Remove all the keys used
        $this->assertEquals(1, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_sorted_sets_zpopmin_outbounds()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $this->assertEquals(1, $this->redis->zAdd($this->key, 1.1, 'A'));
        $this->assertEquals(1, $this->redis->zAdd($this->key, 2.2, 'B'));
        $this->assertEquals(1, $this->redis->zAdd($this->key, 3.3, 'C'));
        $this->assertEquals(3, $this->redis->zCard($this->key));
        // T E S T  -----------------------------------------------------------
        $min = $this->redis->zPopMin($this->key, 5);
        $this->assertIsArray($min);
        $this->assertArraySubset(['A' => 1.1], $min);
        $this->assertArraySubset(['B' => 2.2], $min);
        $this->assertArraySubset(['C' => 3.3], $min);
        $this->assertEquals(0, $this->redis->zCard($this->key));
        // Remove all the keys used
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_sorted_sets_zpopmin_multiple()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $this->assertEquals(1, $this->redis->zAdd($this->key, 1.1, 'A'));
        $this->assertEquals(1, $this->redis->zAdd($this->key, 2.2, 'B'));
        $this->assertEquals(1, $this->redis->zAdd($this->key, 3.3, 'C'));
        $this->assertEquals(3, $this->redis->zCard($this->key));
        // T E S T  -----------------------------------------------------------
        $min = $this->redis->zPopMin($this->key, 2);
        $this->assertIsArray($min);
        $this->assertArraySubset(['A' => 1.1], $min);
        $this->assertArraySubset(['B' => 2.2], $min);
        $this->assertEquals(1, $this->redis->zCard($this->key));
        // Remove all the keys used
        $this->assertEquals(1, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_sorted_sets_zpopmin_default()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $this->assertEquals(1, $this->redis->zAdd($this->key, 1.1, 'A'));
        $this->assertEquals(1, $this->redis->zAdd($this->key, 2.2, 'B'));
        $this->assertEquals(1, $this->redis->zAdd($this->key, 3.3, 'C'));
        $this->assertEquals(3, $this->redis->zCard($this->key));
        // T E S T  -----------------------------------------------------------
        $min = $this->redis->zPopMin($this->key);
        $this->assertIsArray($min);
        $this->assertEquals(['A' => 1.1], $min);
        $this->assertEquals(2, $this->redis->zCard($this->key));
        // Remove all the keys used
        $this->assertEquals(1, $this->redis->delete($this->key));
    }
}
