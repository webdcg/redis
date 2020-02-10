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
        $this->redis->select(0);
        $this->key = 'Introspection';
        $this->keyOptional = 'Introspection:Optional';
    }


    /** @test */
    public function redis_introspection_isConnected()
    {
        $this->assertTrue($this->redis->isConnected());
        $this->assertTrue($this->redis->close());
        usleep(1000 * 10);
        $this->redis = new Redis();
        $this->assertFalse($this->redis->isConnected());
    }


    // Redis | Introspection | getHost => Retrieve our host or unix socket that we're connected to
    /** @test */
    public function redis_introspection_getHost()
    {
        $this->assertEquals('127.0.0.1', $this->redis->getHost());
        $this->assertTrue($this->redis->close());
        $this->redis = new Redis();
        $this->assertFalse($this->redis->getHost());
    }


    // Redis | Introspection | getPort => Retrieve our host or unix socket that we're connected to
    /** @test */
    public function redis_introspection_getPort()
    {
        $this->assertEquals(6379, $this->redis->getPort());
        $this->assertTrue($this->redis->close());
        $this->redis = new Redis();
        $this->assertFalse($this->redis->getPort());
    }


    // Redis | Introspection | getDbNum => Get the database number phpredis is pointed to
    /** @test */
    public function redis_introspection_getDbNum()
    {
        $this->assertEquals(0, $this->redis->getDbNum());
        $this->assertTrue($this->redis->select(1));
        $this->assertEquals(1, $this->redis->getDbNum());
        $this->assertTrue($this->redis->close());
        $this->redis = new Redis();
        $this->assertFalse($this->redis->getDbNum());
    }


    // Redis | Introspection | getTimeout => Retrieve our host or unix socket that we're connected to
    /** @test */
    public function redis_introspection_getTimeout()
    {
        $this->assertEquals(false, $this->redis->getTimeout());
        $this->assertTrue($this->redis->close());
        $this->assertTrue($this->redis->connect('127.0.0.1', 6379, 0.5));
        $this->assertEquals(0.5, $this->redis->getTimeout());
        $this->assertTrue($this->redis->close());
        $this->redis = new Redis();
        $this->assertFalse($this->redis->getTimeout());
    }


    // Redis | Introspection | getReadTimeout => Retrieve our host or unix socket that we're connected to
    /** @test */
    public function redis_introspection_getReadTimeout()
    {
        $timeout = random_int(10, 99);
        $this->assertEquals(false, $this->redis->getReadTimeout());
        $this->assertTrue($this->redis->close());
        $this->assertTrue($this->redis->connect('127.0.0.1', 6379, 0.5));
        $this->assertTrue($this->redis->setOption(\Redis::OPT_READ_TIMEOUT, $timeout));
        $this->assertEquals($timeout, $this->redis->getReadTimeout());
        $this->assertTrue($this->redis->close());
        $this->redis = new Redis();
        $this->assertFalse($this->redis->getReadTimeout());
    }


    // Redis | Introspection | getPersistentID => Gets the persistent ID that phpredis is using
    /** @test */
    public function redis_introspection_getPersistentID()
    {
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $this->assertEquals(null, $this->redis->getPersistentID());
        $this->assertTrue($this->redis->close());
        $this->redis = new Redis();
        $this->assertFalse($this->redis->getPersistentID());
        $this->assertTrue($this->redis->pconnect('127.0.0.1', 6379, 0, 'x'));
        $this->assertTrue($this->redis->set($this->key, 'value'));
        $this->assertEquals('value', $this->redis->get($this->key));
        /**
         * ToDo: Fix and Retest getPersistentID when there's a connection
         */
        // $this->assertEquals('x', $this->redis->getPersistentID());
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
    }
}
