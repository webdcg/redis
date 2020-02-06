<?php

namespace Webdcg\Redis\Tests;

use PHPUnit\Framework\TestCase;
use Webdcg\Redis\Redis;

class zIncrByTest extends TestCase
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
        $this->key = 'SortedSets:zIncrBy';
        $this->keyOptional = 'SortedSets:zIncrBy:Optional';
    }

    /*
     * ========================================================================
     * zIncrBy
     * ========================================================================
     */

    /** @test */
    public function redis_sorted_sets_zincrby_negative()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $this->assertEquals(1, $this->redis->zAdd($this->key, 10.0, 'A'));
        // T E S T  -----------------------------------------------------------
        $this->assertEquals(5.0, $this->redis->zIncrBy($this->key, -5.0, 'A'));
        $this->assertEquals(6.5, $this->redis->zIncrBy($this->key, 1.5, 'A'));
        // Remove all the keys used
        $this->assertEquals(1, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_sorted_sets_zincrby_positive()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $this->assertEquals(1, $this->redis->zAdd($this->key, 1.0, 'A'));
        // T E S T  -----------------------------------------------------------
        $this->assertEquals(2.0, $this->redis->zIncrBy($this->key, 1.0, 'A'));
        $this->assertEquals(3.5, $this->redis->zIncrBy($this->key, 1.5, 'A'));
        // Remove all the keys used
        $this->assertEquals(1, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_sorted_sets_zincrby_missing_member()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $this->assertEquals(1, $this->redis->zAdd($this->key, 1.0, 'A'));
        // T E S T  -----------------------------------------------------------
        $this->assertEquals(1.0, $this->redis->zIncrBy($this->key, 1.0, 'B'));
        // Remove all the keys used
        $this->assertEquals(1, $this->redis->delete($this->key));
    }
}
