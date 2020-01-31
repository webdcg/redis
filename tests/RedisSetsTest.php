<?php

namespace Webdcg\Redis\Tests;

use PHPUnit\Framework\TestCase;
use Webdcg\Redis\Redis;

class RedisSetsTest extends TestCase
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
        $this->key = 'Sets';
        $this->keyOptional = 'Sets:Optional';
    }

    /** @test */
    public function redis_sets_sdiffstore_simple()
    {
        $destinationKey = $this->key . ':' . $this->keyOptional;
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->keyOptional));
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($destinationKey));

        $this->assertEquals(1, $this->redis->sAdd($this->key, 'A'));
        $this->assertEquals(1, $this->redis->sAdd($this->key, 'B'));
        $this->assertEquals(1, $this->redis->sAdd($this->keyOptional, 'B'));

        $this->assertEquals(1, $this->redis->sDiffStore($destinationKey, $this->key, $this->keyOptional));
        $this->assertEquals(1, $this->redis->sCard($destinationKey));

        // Cleanup
        $this->assertEquals(1, $this->redis->delete($this->key));
        $this->assertEquals(1, $this->redis->delete($this->keyOptional));
        $this->assertEquals(1, $this->redis->delete($destinationKey));
    }

    /** @test */
    public function redis_sets_sdiff_simple()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->keyOptional));
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key . ':' . $this->keyOptional));
        $this->assertEquals(1, $this->redis->sAdd($this->key, 'A'));
        $this->assertEquals(1, $this->redis->sAdd($this->key, 'B'));
        $this->assertEquals(1, $this->redis->sAdd($this->keyOptional, 'B'));
        $this->assertEquals(['A'], $this->redis->sDiff($this->key, $this->keyOptional));
        $this->assertEquals(1, $this->redis->sAdd($this->key, 'Z'));
        $this->assertEquals(1, $this->redis->sAdd($this->keyOptional, 'C'));
        $this->assertEquals(1, $this->redis->sAdd($this->key . ':' . $this->keyOptional, 'D'));
        $this->assertEquals(1, $this->redis->sAdd($this->key . ':' . $this->keyOptional, 'E'));
        $this->assertEquals(['A', 'Z'], $this->redis->sDiff($this->key, $this->keyOptional, $this->key . ':' . $this->keyOptional));
        $this->assertEquals(1, $this->redis->delete($this->key));
        $this->assertEquals(1, $this->redis->delete($this->keyOptional));
        $this->assertEquals(1, $this->redis->delete($this->key . ':' . $this->keyOptional));
    }

    /** @test */
    public function redis_sets_ssize_multiple()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $this->assertEquals(3, $this->redis->sAdd($this->key, 'A', 'B', 'C'));
        $this->assertEquals(3, $this->redis->sSize($this->key));
        $this->assertEquals(1, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_sets_ssize_duplicate()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $this->assertEquals(1, $this->redis->sAdd($this->key, 'A'));
        $this->assertEquals(0, $this->redis->sAdd($this->key, 'A'));
        $this->assertEquals(1, $this->redis->sSize($this->key));
        $this->assertEquals(1, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_sets_ssize_single()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $this->assertEquals(1, $this->redis->sAdd($this->key, 'A'));
        $this->assertEquals(1, $this->redis->sSize($this->key));
        $this->assertEquals(1, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_sets_scard_multiple()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $this->assertEquals(3, $this->redis->sAdd($this->key, 'A', 'B', 'C'));
        $this->assertEquals(3, $this->redis->sCard($this->key));
        $this->assertEquals(1, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_sets_scard_duplicate()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $this->assertEquals(1, $this->redis->sAdd($this->key, 'A'));
        $this->assertEquals(0, $this->redis->sAdd($this->key, 'A'));
        $this->assertEquals(1, $this->redis->sCard($this->key));
        $this->assertEquals(1, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_sets_scard_single()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $this->assertEquals(1, $this->redis->sAdd($this->key, 'A'));
        $this->assertEquals(1, $this->redis->sCard($this->key));
        $this->assertEquals(1, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_sets_sadd_array()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $this->assertEquals(3, $this->redis->sAdd($this->key, ['A', 'B', 'C']));
        $this->assertEquals(1, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_sets_sadd_multiple()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $this->assertEquals(3, $this->redis->sAdd($this->key, 'A', 'B', 'C'));
        $this->assertEquals(1, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_sets_sadd_duplicate()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $this->assertEquals(1, $this->redis->sAdd($this->key, 'A'));
        $this->assertEquals(0, $this->redis->sAdd($this->key, 'A'));
        $this->assertEquals(1, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_sets_sadd_single()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $this->assertEquals(1, $this->redis->sAdd($this->key, 'A'));
        $this->assertEquals(1, $this->redis->delete($this->key));
    }
}
