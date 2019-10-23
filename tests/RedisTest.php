<?php

namespace Webdcg\Redis\Tests;

use PHPUnit\Framework\TestCase;
use Webdcg\Redis\Redis;

class RedisTest extends TestCase
{
    /** @test */
    public function true_is_true()
    {
        $this->assertTrue(true);
    }

    /** @test */
    public function redis_proper_connection()
    {
        $redis = new Redis;
        $this->assertTrue($redis->connect('127.0.0.1', 6379));
    }

    /** @test */
    public function redis_wonrg_connection()
    {
        $redis = new Redis;
        $this->expectException(\RedisException::class);
        $this->assertFalse($redis->connect('127.0.0.1', 9736));
    }
}
