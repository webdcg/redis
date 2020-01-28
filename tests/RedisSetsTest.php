<?php

namespace Webdcg\Redis\Tests;

use Exception;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Process\PhpProcess;
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
