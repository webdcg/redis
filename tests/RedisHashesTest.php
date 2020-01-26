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
    public function redis_hashes_hstrlen()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $this->assertEquals(1, $this->redis->hSet($this->key, 'tswift', 'Taylor Swift'));
        $this->assertEquals('Taylor Swift', $this->redis->hGet($this->key, 'tswift'));
        $this->assertEquals(1, $this->redis->hSet($this->key, 'millaj', 'Milla Jovovich'));
        $this->assertEquals('Milla Jovovich', $this->redis->hGet($this->key, 'millaj'));
        // --------------------  T E S T  --------------------
        $this->assertEquals(12, $this->redis->hStrLen($this->key, 'tswift'));
        $this->assertEquals(14, $this->redis->hStrLen($this->key, 'millaj'));
        // Cleanup used keys
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_hashes_hmget()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $hash = [
            'tswift' => 'Taylor Swift',
            'millaj' => 'Milla Jovovich',
            'kbeck' => 'Kate Beckinsale',
        ];
        $this->assertTrue($this->redis->hMSet($this->key, $hash));
        // --------------------  T E S T  --------------------
        $this->assertEquals($hash, $this->redis->hMGet($this->key, ['tswift', 'millaj', 'kbeck']));
        // Cleanup used keys
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_hashes_hmset()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $hash = [
            'tswift' => 'Taylor Swift',
            'millaj' => 'Milla Jovovich',
            'kbeck' => 'Kate Beckinsale',
        ];
        // --------------------  T E S T  --------------------
        $this->assertTrue($this->redis->hMSet($this->key, $hash));
        $this->assertEquals($hash['tswift'], $this->redis->hGet($this->key, 'tswift'));
        $this->assertEquals($hash['millaj'], $this->redis->hGet($this->key, 'millaj'));
        $this->assertEquals($hash['kbeck'], $this->redis->hGet($this->key, 'kbeck'));
        // --------------------  T E S T  --------------------
        // Cleanup used keys
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_hashes_hincrbyfloat()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $this->assertEquals(1, $this->redis->hSet($this->key, 'field', 1));
        $this->assertEquals(1, $this->redis->hGet($this->key, 'field'));
        // --------------------  T E S T  --------------------
        $this->assertEquals(2.0, $this->redis->hIncrByFloat($this->key, 'field'));
        $this->assertEquals(4.5, $this->redis->hIncrByFloat($this->key, 'field', 2.5));
        $this->assertEquals(3.3, $this->redis->hIncrByFloat($this->key, 'field', -1.2));
        $this->assertIsFloat($this->redis->hIncrByFloat($this->key, 'field', 1));
        // Cleanup used keys
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_hashes_hincrby()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $this->assertEquals(1, $this->redis->hSet($this->key, 'field', 1));
        $this->assertEquals(1, $this->redis->hGet($this->key, 'field'));
        // --------------------  T E S T  --------------------
        $this->assertEquals(2, $this->redis->hIncrBy($this->key, 'field'));
        $this->assertEquals(4, $this->redis->hIncrBy($this->key, 'field', 2));
        $this->assertEquals(3, $this->redis->hIncrBy($this->key, 'field', -1));
        $this->assertIsInt($this->redis->hIncrBy($this->key, 'field', 1));
        // Cleanup used keys
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_hashes_hexists()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $this->assertEquals(1, $this->redis->hSet($this->key, 'field', 'value'));
        // --------------------  T E S T  --------------------
        $this->assertTrue($this->redis->hExists($this->key, 'field'));
        $this->assertFalse($this->redis->hExists($this->key, 'field2'));
        // Cleanup used keys
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_hashes_hgetall()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $this->assertEquals(1, $this->redis->hSet($this->key, 'field', 'value'));
        $this->assertEquals('value', $this->redis->hGet($this->key, 'field'));
        $this->assertTrue($this->redis->hSetNx($this->key, 'field2', 'value2'));
        $this->assertEquals('value2', $this->redis->hGet($this->key, 'field2'));
        // --------------------  T E S T  --------------------
        $this->assertSame(['field', 'field2'], $this->redis->hKeys($this->key));
        $this->assertEquals(['value', 'value2'], $this->redis->hVals($this->key));
        $this->assertSame(['field' => 'value', 'field2' => 'value2'], $this->redis->hGetAll($this->key));
        $this->assertEquals(['field' => 'value', 'field2' => 'value2'], $this->redis->hGetAll($this->key));
        // Cleanup used keys
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_hashes_hvals()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $this->assertEquals(1, $this->redis->hSet($this->key, 'field', 'value'));
        $this->assertEquals('value', $this->redis->hGet($this->key, 'field'));
        $this->assertTrue($this->redis->hSetNx($this->key, 'field2', 'value2'));
        $this->assertEquals('value2', $this->redis->hGet($this->key, 'field2'));
        // --------------------  T E S T  --------------------
        $this->assertSame(['value', 'value2'], $this->redis->hVals($this->key));
        $this->assertEquals(['value', 'value2'], $this->redis->hVals($this->key));
        // Cleanup used keys
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_hashes_hkeys()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $this->assertEquals(1, $this->redis->hSet($this->key, 'field', 'value'));
        $this->assertEquals('value', $this->redis->hGet($this->key, 'field'));
        $this->assertTrue($this->redis->hSetNx($this->key, 'field2', 'value2'));
        $this->assertEquals('value2', $this->redis->hGet($this->key, 'field2'));
        // --------------------  T E S T  --------------------
        $this->assertSame(['field', 'field2'], $this->redis->hKeys($this->key));
        $this->assertEquals(['field', 'field2'], $this->redis->hKeys($this->key));
        // Cleanup used keys
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
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
