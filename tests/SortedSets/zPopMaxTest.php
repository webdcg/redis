<?php

namespace Webdcg\Redis\Tests;

use PHPUnit\Framework\TestCase;
use Webdcg\Redis\Redis;

class zPopMaxTest extends TestCase
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
        $this->key = 'SortedSets:zPopMax';
        $this->keyOptional = 'SortedSets:zPopMax:Optional';
    }

    /*
     * ========================================================================
     * zPopMax
     * ========================================================================
     */

    /** @test */
    public function redis_sorted_sets_zpop_max()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $this->assertEquals(1, $this->redis->zAdd($this->key, 1.1, 'A'));
        $this->assertEquals(1, $this->redis->zAdd($this->key, 2.2, 'B'));
        $this->assertEquals(1, $this->redis->zAdd($this->key, 3.3, 'C'));
        $this->assertEquals(3, $this->redis->zCard($this->key));
        // T E S T  -----------------------------------------------------------
        $max = $this->redis->zPop($this->key, 2, true);
        $this->assertIsArray($max);
        $this->assertArraySubset(['B' => 2.2], $max);
        $this->assertArraySubset(['C' => 3.3], $max);
        $this->assertEquals(1, $this->redis->zCard($this->key));
        // Remove all the keys used
        $this->assertEquals(1, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_sorted_sets_zpopmax_outbounds()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $this->assertEquals(1, $this->redis->zAdd($this->key, 1.1, 'A'));
        $this->assertEquals(1, $this->redis->zAdd($this->key, 2.2, 'B'));
        $this->assertEquals(1, $this->redis->zAdd($this->key, 3.3, 'C'));
        $this->assertEquals(3, $this->redis->zCard($this->key));
        // T E S T  -----------------------------------------------------------
        $max = $this->redis->zPopMax($this->key, 5);
        $this->assertIsArray($max);
        $this->assertArraySubset(['A' => 1.1], $max);
        $this->assertArraySubset(['B' => 2.2], $max);
        $this->assertArraySubset(['C' => 3.3], $max);
        $this->assertEquals(0, $this->redis->zCard($this->key));
        // Remove all the keys used
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_sorted_sets_zpopmax_multiple()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $this->assertEquals(1, $this->redis->zAdd($this->key, 1.1, 'A'));
        $this->assertEquals(1, $this->redis->zAdd($this->key, 2.2, 'B'));
        $this->assertEquals(1, $this->redis->zAdd($this->key, 3.3, 'C'));
        $this->assertEquals(3, $this->redis->zCard($this->key));
        // T E S T  -----------------------------------------------------------
        $max = $this->redis->zPopMax($this->key, 2);
        $this->assertIsArray($max);
        $this->assertArraySubset(['B' => 2.2], $max);
        $this->assertArraySubset(['C' => 3.3], $max);
        $this->assertEquals(1, $this->redis->zCard($this->key));
        // Remove all the keys used
        $this->assertEquals(1, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_sorted_sets_zpopmax_default()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $this->assertEquals(1, $this->redis->zAdd($this->key, 1.1, 'A'));
        $this->assertEquals(1, $this->redis->zAdd($this->key, 2.2, 'B'));
        $this->assertEquals(1, $this->redis->zAdd($this->key, 3.3, 'C'));
        $this->assertEquals(3, $this->redis->zCard($this->key));
        // T E S T  -----------------------------------------------------------
        $max = $this->redis->zPopMax($this->key);
        $this->assertIsArray($max);
        $this->assertEquals(['C' => 3.3], $max);
        $this->assertEquals(2, $this->redis->zCard($this->key));
        // Remove all the keys used
        $this->assertEquals(1, $this->redis->delete($this->key));
    }
}
