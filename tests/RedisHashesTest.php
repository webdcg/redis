<?php

namespace Webdcg\Redis\Tests;

use PHPUnit\Framework\TestCase;
use Webdcg\Redis\Redis;

class RedisHashesTest extends TestCase
{
    protected $redis;
    protected $key;

    protected function setUp(): void
    {
        $this->redis = new Redis();
        $this->redis->connect();
        $this->redis->setOption(\Redis::OPT_SERIALIZER, \Redis::SERIALIZER_NONE);
        $this->key = 'Hashes';
    }

    /** @test */
    public function redis_hashes_hlen()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $this->assertEquals(1, $this->redis->hSet($this->key, 'field', 'value'));
        $this->assertEquals('value', $this->redis->hGet($this->key, 'field'));
        // --------------------  T E S T  --------------------
        $this->assertEquals(1, $this->redis->hLen($this->key));
        $this->assertTrue($this->redis->hSetNx($this->key, 'field2', 'value2'));
        $this->assertEquals(2, $this->redis->hLen($this->key));
        $this->assertEquals(1, $this->redis->hDel($this->key, 'field'));
        $this->assertEquals(1, $this->redis->hLen($this->key));
        // Cleanup used keys
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_hashes_hsetnx()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $this->assertEquals(1, $this->redis->hSet($this->key, 'field', 'value'));
        $this->assertEquals('value', $this->redis->hGet($this->key, 'field'));
        // --------------------  T E S T  --------------------
        $this->assertTrue($this->redis->hSetNx($this->key, 'field2', 'value2'));
        $this->assertFalse($this->redis->hSetNx($this->key, 'field', 'value'));
        // Cleanup used keys
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_hashes_hdel()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $this->assertEquals(1, $this->redis->hSet($this->key, 'field', 'value'));
        $this->assertEquals('value', $this->redis->hGet($this->key, 'field'));
        // --------------------  T E S T  --------------------
        $this->assertEquals(1, $this->redis->hDel($this->key, 'field'));
        // --------------------  T E S T  --------------------
        $this->assertFalse($this->redis->hGet($this->key, 'field'));
        // Cleanup used keys
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_hashes_hget()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $this->assertEquals(1, $this->redis->hSet($this->key, 'field', 'value'));
        // --------------------  T E S T  --------------------
        $this->assertEquals('value', $this->redis->hGet($this->key, 'field'));
        // Cleanup used keys
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_hashes_hset()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        // --------------------  T E S T  --------------------
        $this->assertEquals(1, $this->redis->hSet($this->key, 'field', 'value'));
        // --------------------  T E S T  --------------------
        $this->assertEquals('value', $this->redis->hGet($this->key, 'field'));
        // Cleanup used keys
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
    }
}
