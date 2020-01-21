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
    public function redis_connection_setoption_prefix()
    {
        $this->assertTrue($this->redis->setOption(\Redis::OPT_PREFIX, 'redis:'));
    }

    /** @test */
    public function redis_connection_getoption_prefix_string()
    {
        $this->assertTrue($this->redis->setOption(\Redis::OPT_PREFIX, 'redis:'));
        $this->assertEquals('redis:', $this->redis->getOption(\Redis::OPT_PREFIX));
    }

    /** @test */
    public function redis_connection_getoption_prefix_integer()
    {
        $date = (int) date('Ymd');
        $this->assertTrue($this->redis->setOption(\Redis::OPT_PREFIX, $date));
        $this->assertEquals($date, $this->redis->getOption(\Redis::OPT_PREFIX));
    }

    /** @test */
    public function redis_connection_options_prefix_string()
    {
        $this->redis_connection_getoption_prefix_string();
        // Actual key name will be redis:key
        $this->assertTrue($this->redis->set('key', 'value'));
        // We're actually fetching redis:key
        $this->assertEquals('value', $this->redis->get('key'));
        $this->assertEquals(1, $this->redis->delete('key'));
        $this->assertEquals(0, $this->redis->exists('key'));
    }

    /** @test */
    public function redis_connection_options_prefix_integer()
    {
        $this->redis_connection_getoption_prefix_integer();
        // Actual key name will be 20200104key
        $this->assertTrue($this->redis->set('key', 'value'));
        // We're actually fetching 20200104key
        $this->assertEquals('value', $this->redis->get('key'));
        $this->assertEquals(1, $this->redis->delete('key'));
        $this->assertEquals(0, $this->redis->exists('key'));
    }

    /** @test */
    public function redis_connection_options_serializer_none()
    {
        $this->assertTrue($this->redis->setOption(\Redis::OPT_SERIALIZER, \Redis::SERIALIZER_NONE));
        $this->assertEquals(\Redis::SERIALIZER_NONE, $this->redis->getOption(\Redis::OPT_SERIALIZER));
        $this->assertTrue($this->redis->set('keySerializerNone', 'value'));
        $this->assertEquals('value', $this->redis->get('keySerializerNone'));
        $this->assertEquals(1, $this->redis->delete('keySerializerNone'));
        $this->assertEquals(0, $this->redis->exists('keySerializerNone'));
    }

    /** @test */
    public function redis_connection_options_serializer_php()
    {
        $this->assertTrue($this->redis->setOption(\Redis::OPT_SERIALIZER, \Redis::SERIALIZER_PHP));
        $this->assertEquals(\Redis::SERIALIZER_PHP, $this->redis->getOption(\Redis::OPT_SERIALIZER));
        $this->assertTrue($this->redis->set('keySerializerPHP', 'value'));
        $this->assertEquals('value', $this->redis->get('keySerializerPHP'));
        $this->assertEquals(1, $this->redis->delete('keySerializerPHP'));
        $this->assertEquals(0, $this->redis->exists('keySerializerPHP'));
    }

    // ToDo: Test with igBinary
    public function redis_connection_options_serializer_igbinary()
    {
        $this->assertTrue($this->redis->setOption(\Redis::OPT_SERIALIZER, \Redis::SERIALIZER_IGBINARY));
        $this->assertEquals(\Redis::SERIALIZER_IGBINARY, $this->redis->getOption(\Redis::OPT_SERIALIZER));
        $this->assertTrue($this->redis->set('keySerializerIgBinary', 'value'));
        $this->assertEquals('value', $this->redis->get('keySerializerIgBinary'));
        $this->assertEquals(1, $this->redis->delete('keySerializerIgBinary'));
        $this->assertEquals(0, $this->redis->exists('keySerializerIgBinary'));
    }

    // ToDo: Test with msgpack
    public function redis_connection_options_serializer_msgpack()
    {
        $this->assertTrue($this->redis->setOption(\Redis::OPT_SERIALIZER, \Redis::SERIALIZER_MSGPACK));
        $this->assertEquals(\Redis::SERIALIZER_MSGPACK, $this->redis->getOption(\Redis::OPT_SERIALIZER));
        $this->assertTrue($this->redis->set('keySerializerMsgPack', 'value'));
        $this->assertEquals('value', $this->redis->get('keySerializerMsgPack'));
        $this->assertEquals(1, $this->redis->delete('keySerializerMsgPack'));
        $this->assertEquals(0, $this->redis->exists('keySerializerMsgPack'));
    }

    /** @test */
    public function redis_connection_ping()
    {
        $this->assertEquals('pong', $this->redis->ping('pong'));
        $this->assertEquals('redis', $this->redis->ping('redis'));
        $this->assertTrue($this->redis->ping());
    }

    /** @test */
    public function redis_connection_echo()
    {
        $this->assertEquals('redis', $this->redis->echo('redis'));
        $this->assertNotEquals('sider', $this->redis->echo('redis'));
    }
}
