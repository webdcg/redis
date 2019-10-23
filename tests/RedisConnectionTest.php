<?php

namespace Webdcg\Redis\Tests;

use Webdcg\Redis\Redis;
use PHPUnit\Framework\TestCase;

class RedisConnectionTest extends TestCase
{
    /** @test */
    public function true_is_true()
    {
        $this->assertTrue(true);
    }

    /** @test */
    public function redis_connection_connect()
    {
        $redis = new Redis;
        $this->assertTrue($redis->connect());
        $this->assertTrue($redis->connect('127.0.0.1'));
        $this->assertTrue($redis->connect('127.0.0.1', 6379));
        $this->assertTrue($redis->connect('127.0.0.1', 6379, 0.5));
        $this->assertTrue($redis->connect('127.0.0.1', 6379, 0.5, null, 100));
    }

    /** @test */
    public function redis_connection_open()
    {
        $redis = new Redis;
        $this->assertTrue($redis->open());
        $this->assertTrue($redis->open('127.0.0.1'));
        $this->assertTrue($redis->open('127.0.0.1', 6379));
        $this->assertTrue($redis->open('127.0.0.1', 6379, 0.5));
        $this->assertTrue($redis->open('127.0.0.1', 6379, 0.5, null, 100));
    }

    /** @test */
    public function redis_connection_connect_exception()
    {
        $redis = new Redis;
        $this->expectException(\RedisException::class);
        $this->assertFalse($redis->connect('127.0.0.1', 9736));
    }

    /** @test */
    public function redis_connection_open_exception()
    {
        $redis = new Redis;
        $this->expectException(\RedisException::class);
        $this->assertFalse($redis->open('127.0.0.1', 9736));
    }

    /** @test */
    public function redis_connection_persistent()
    {
        $redis = new Redis;
        $this->assertTrue($redis->pconnect('127.0.0.1', 6379, 0, 'x'));
    }

    /** @test */
    public function redis_connection_persistent_exception()
    {
        $redis = new Redis;
        $this->expectException(\RedisException::class);
        $this->assertTrue($redis->pconnect('127.0.0.1', 9736, 0, 'x'));
    }

    /** @test */
    public function redis_connection_authenticate()
    {
        $redis = new Redis;
        $this->assertTrue($redis->connect('127.0.0.1', 6380));
        $this->assertTrue($redis->auth('secret'));
    }

    /** @test */
    public function redis_connection_authenticate_exception()
    {
        $redis = new Redis;
        $this->assertTrue($redis->connect('127.0.0.1', 6380));
        $this->assertFalse($redis->auth('password'));
    }
}
