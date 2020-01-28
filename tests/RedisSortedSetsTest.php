<?php

namespace Webdcg\Redis\Tests;

use PHPUnit\Framework\TestCase;
use Webdcg\Redis\Redis;

class RedisSortedSetsTest extends TestCase
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
        $this->key = 'SortedSets';
        $this->keyOptional = 'SortedSets:Optional';
    }

    /** @test */
    public function redis_sorted_sets_zadd_multiple()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $this->assertEquals(1, $this->redis->zAdd($this->key, 1.0, 'A'));
        $this->assertEquals(1, $this->redis->zAdd($this->key, 2.0, 'B'));
        $this->assertEquals(1, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_sorted_sets_zadd_duplicate()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $this->assertEquals(1, $this->redis->zAdd($this->key, 1.0, 'A'));
        $this->assertEquals(0, $this->redis->zAdd($this->key, 2.0, 'A'));
        $this->assertEquals(1, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_sorted_sets_zadd_single()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $this->assertEquals(1, $this->redis->zAdd($this->key, 1.0, 'A'));
        $this->assertEquals(1, $this->redis->delete($this->key));
    }
}
