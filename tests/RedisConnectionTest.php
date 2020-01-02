<?php

namespace Webdcg\Redis\Tests;

use PHPUnit\Framework\TestCase;
use Webdcg\Redis\Redis;

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
        $this->assertTrue($this->redis->popen('127.0.0.1', 6379, 0, 'x'));
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
        $dbFrom = 0;
        $dbTo = 1;
        $this->assertTrue($this->redis->swapdb($dbFrom, $dbTo));
    }

    /** @test */
    public function redis_connection_swapdb_out_range()
    {
        $dbFrom = 15;
        $dbTo = 16;
        $this->assertFalse($this->redis->swapdb($dbFrom, $dbTo));
    }

    /** @test */
    public function redis_connection_close()
    {
        $this->assertTrue($this->redis->close());
    }

    /** @test */
    public function redis_connection_setoption()
    {
        $this->assertTrue($this->redis->setOption(\Redis::OPT_PREFIX, 'redis:'));
    }

    /** @test */
    public function redis_connection_getoption()
    {
        $this->assertTrue($this->redis->setOption(\Redis::OPT_PREFIX, 'redis:'));
        $this->assertEquals('redis:', $this->redis->getOption(\Redis::OPT_PREFIX));
    }

    /** @test */
    public function redis_connection_ping()
    {
        $this->assertEquals('pong', $this->redis->ping('pong'));
        $this->assertTrue($this->redis->ping());
    }

    /** @test */
    public function redis_connection_echo()
    {
        $this->assertEquals('redis', $this->redis->echo('redis'));
    }
}
