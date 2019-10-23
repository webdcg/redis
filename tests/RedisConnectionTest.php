<?php

namespace Webdcg\Redis\Tests;

use Webdcg\Redis\Redis;
use PHPUnit\Framework\TestCase;

class RedisConnectionTest extends TestCase
{
    protected $redis;

    protected function setUp(): void
    {
        $this->redis = new Redis;
        $this->redis->connect();
    }

    /** @test */
    public function redis_connection_connect()
    {
        $this->assertTrue($this->redis->connect());
        $this->assertTrue($this->redis->connect('127.0.0.1'));
        $this->assertTrue($this->redis->connect('127.0.0.1', 6379));
        $this->assertTrue($this->redis->connect('127.0.0.1', 6379, 0.5));
        $this->assertTrue($this->redis->connect('127.0.0.1', 6379, 0.5, null, 100));
    }

    /** @test */
    public function redis_connection_open()
    {
        $this->assertTrue($this->redis->open());
        $this->assertTrue($this->redis->open('127.0.0.1'));
        $this->assertTrue($this->redis->open('127.0.0.1', 6379));
        $this->assertTrue($this->redis->open('127.0.0.1', 6379, 0.5));
        $this->assertTrue($this->redis->open('127.0.0.1', 6379, 0.5, null, 100));
    }

    /** @test */
    public function redis_connection_connect_exception()
    {
        $this->expectException(\RedisException::class);
        $this->assertFalse($this->redis->connect('127.0.0.1', 9736));
    }

    /** @test */
    public function redis_connection_open_exception()
    {
        $this->expectException(\RedisException::class);
        $this->assertFalse($this->redis->open('127.0.0.1', 9736));
    }

    /** @test */
    public function redis_connection_persistent()
    {
        $this->assertTrue($this->redis->pconnect('127.0.0.1', 6379, 0, 'x'));
    }

    /** @test */
    public function redis_connection_persistent_exception()
    {
        $this->expectException(\RedisException::class);
        $this->assertTrue($this->redis->pconnect('127.0.0.1', 9736, 0, 'x'));
    }

    /** @test */
    public function redis_connection_authenticate()
    {
        $this->assertTrue($this->redis->connect('127.0.0.1', 6380));
        $this->assertTrue($this->redis->auth('secret'));
    }

    /** @test */
    public function redis_connection_authenticate_exception()
    {
        $this->assertTrue($this->redis->connect('127.0.0.1', 6380));
        $this->assertFalse($this->redis->auth('password'));
    }

    /** @test */
    public function redis_connection_select()
    {
        for ($db = 0; $db < 16; $db++) {
            $this->assertTrue($this->redis->select($db));
        }
    }

    /** @test */
    public function redis_connection_select_out_range()
    {
        $this->assertFalse($this->redis->select(17));
    }

    /** @test */
    public function redis_connection_swapdb()
    {
        $this->assertTrue($this->redis->swapdb(0, 1));
    }

    /** @test */
    public function redis_connection_swapdb_out_range()
    {
        $this->assertFalse($this->redis->swapdb(15, 16));
    }

    /** @test */
    public function redis_connection_close()
    {
        $this->assertTrue($this->redis->close());
    }
}
