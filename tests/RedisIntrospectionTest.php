<?php

namespace Webdcg\Redis\Tests;

use PHPUnit\Framework\TestCase;
use Webdcg\Redis\Redis;

class RedisIntrospectionTest extends TestCase
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
        $this->key = 'Introspection';
        $this->keyOptional = 'Introspection:Optional';
    }


    /** @test */
    public function redis_introspection_isConnected()
    {
        $this->assertTrue($this->redis->isConnected());
    }

    // Redis | Introspection | getHost => Retrieve our host or unix socket that we're connected to
    /** @test */
    public function redis_introspection_getHost()
    {
        $this->assertEquals('127.0.0.1', $this->redis->getHost());
    }
}
