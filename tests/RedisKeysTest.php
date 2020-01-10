<?php

namespace Webdcg\Redis\Tests;

use PHPUnit\Framework\TestCase;
use Webdcg\Redis\Redis;

class RedisKeysTest extends TestCase
{
    protected $redis;

    protected function setUp(): void
    {
        $this->redis = new Redis;
        $this->redis->connect();
    }

    /** @test */
    public function redis_keys_del_single_key()
    {
        $this->assertTrue($this->redis->set('key1', 'val1'));
        $this->assertEquals(1, $this->redis->del('key1'));
    }

    /** @test */
    public function redis_keys_del_multiple_keys()
    {
        $this->assertTrue($this->redis->set('key1', 'val1'));
        $this->assertTrue($this->redis->set('key2', 'val2'));
        $this->assertEquals(2, $this->redis->del('key1', 'key2'));
    }

    /** @test */
    public function redis_keys_del_array_keys()
    {
        $this->assertTrue($this->redis->set('key1', 'val1'));
        $this->assertTrue($this->redis->set('key2', 'val2'));
        $this->assertEquals(2, $this->redis->del(['key1', 'key2']));
    }

    /** @test */
    public function redis_keys_delete_single_key()
    {
        $this->assertTrue($this->redis->set('key1', 'val1'));
        $this->assertEquals(1, $this->redis->delete('key1'));
    }

    /** @test */
    public function redis_keys_delete_multiple_keys()
    {
        $this->assertTrue($this->redis->set('key1', 'val1'));
        $this->assertTrue($this->redis->set('key2', 'val2'));
        $this->assertEquals(2, $this->redis->delete('key1', 'key2'));
    }

    /** @test */
    public function redis_keys_delete_array_keys()
    {
        $this->assertTrue($this->redis->set('key1', 'val1'));
        $this->assertTrue($this->redis->set('key2', 'val2'));
        $this->assertEquals(2, $this->redis->delete(['key1', 'key2']));
    }
}
