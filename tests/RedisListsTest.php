<?php

namespace Webdcg\Redis\Tests;

use PHPUnit\Framework\TestCase;
use Webdcg\Redis\Redis;

class RedisListsTest extends TestCase
{
    protected $redis;
    protected $key;

    protected function setUp(): void
    {
        $this->redis = new Redis();
        $this->redis->connect();
        $this->redis->setOption(\Redis::OPT_SERIALIZER, \Redis::SERIALIZER_NONE);
        $this->key = 'Lists';
    }

    /** @test */
    public function redis_lists_lpushx_float()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        // --------------------  T E S T  --------------------
        $this->assertEquals(0, $this->redis->lPushx($this->key, 1.1));
        $this->assertEquals(1, $this->redis->lPush($this->key, 1.1));
        $this->assertEquals(2, $this->redis->lPush($this->key, 2.2));
        $this->assertEquals(3, $this->redis->lPush($this->key, 3.3));
        // Cleanup used keys
        $this->assertEquals(1, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_lists_lpushx_int()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        // --------------------  T E S T  --------------------
        $this->assertEquals(0, $this->redis->lPushx($this->key, 11));
        $this->assertEquals(1, $this->redis->lPush($this->key, 11));
        $this->assertEquals(2, $this->redis->lPush($this->key, 22));
        $this->assertEquals(3, $this->redis->lPush($this->key, 33));
        // Cleanup used keys
        $this->assertEquals(1, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_lists_lpushx_string()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        // --------------------  T E S T  --------------------
        $this->assertEquals(0, $this->redis->lPushx($this->key, "A"));
        $this->assertEquals(1, $this->redis->lPush($this->key, "A"));
        $this->assertEquals(2, $this->redis->lPush($this->key, "B"));
        $this->assertEquals(3, $this->redis->lPush($this->key, "C"));
        // Cleanup used keys
        $this->assertEquals(1, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_lists_lpush_float()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        for ($i = 1; $i <= 10; $i++) {
            // --------------------  T E S T  --------------------
            $this->assertEquals($i, $this->redis->lPush($this->key, $i * 1.1));
        }
        // Cleanup used keys
        $this->assertEquals(1, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_lists_lpush_int()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        for ($i = 1; $i <= 10; $i++) {
            // --------------------  T E S T  --------------------
            $this->assertEquals($i, $this->redis->lPush($this->key, $i));
        }
        // Cleanup used keys
        $this->assertEquals(1, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_lists_lpush_string()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        for ($i = 1; $i <= 10; $i++) {
            // --------------------  T E S T  --------------------
            $this->assertEquals($i, $this->redis->lPush($this->key, "Item{$i}"));
        }
        // Cleanup used keys
        $this->assertEquals(1, $this->redis->delete($this->key));
    }
}
