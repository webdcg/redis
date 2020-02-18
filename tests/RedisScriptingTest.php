<?php

namespace Webdcg\Redis\Tests;

use PHPUnit\Framework\TestCase;
use Webdcg\Redis\Redis;

class RedisScriptingTest extends TestCase
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
        $this->key = 'Scripting';
        $this->keyOptional = 'Scripting:Optional';
    }

    /*
     * ========================================================================
     * script
     *
     * Redis | Scripting | _serialize => A utility method to serialize values manually.
     * ========================================================================
     */


    /** @test */
    public function redis_Scripting__serialize_PHP()
    {
        $this->redis->setOption(\Redis::OPT_SERIALIZER, \Redis::SERIALIZER_PHP);
        $this->assertEquals('s:3:"foo";', $this->redis->_serialize('foo')); // Returns 's:3:"foo";'
        $this->assertEquals('a:0:{}', $this->redis->_serialize([])); // Returns 'a:0:{}'
        $this->assertEquals('O:8:"stdClass":0:{}', $this->redis->_serialize(new \stdClass())); // Returns 'O:8:"stdClass":0:{}'
    }


    /** @test */
    public function redis_Scripting__serialize_none()
    {
        $this->redis->setOption(\Redis::OPT_SERIALIZER, \Redis::SERIALIZER_NONE);
        $this->assertEquals('foo', $this->redis->_serialize('foo')); // returns "foo"
        $this->assertEquals('Array', $this->redis->_serialize([])); // Returns "Array"
        $this->assertEquals('Object', $this->redis->_serialize(new \stdClass())); // Returns "Object"
    }


    /** @test */
    public function redis_Scripting__prefix()
    {
        $this->redis->setOption(\Redis::OPT_PREFIX, 'tswift:');
        $prefix = $this->redis->_prefix('miss-americana');
        $this->assertEquals('tswift:miss-americana', $prefix);
    }


    /** @test */
    public function redis_Scripting_clearLastError()
    {
        $this->redis->eval('this-is-not-lua');
        $error = $this->redis->getLastError();
        $this->assertContains('ERR Error compiling script', $error);
        $clear = $this->redis->clearLastError();
        $this->assertTrue($clear);
        $this->assertIsBool($clear);
        $error = $this->redis->getLastError();
        $this->assertNull($error);
    }


    /** @test */
    public function redis_Scripting_getLastError()
    {
        $this->redis->eval('this-is-not-lua');
        $error = $this->redis->getLastError();
        $this->assertContains('ERR Error compiling script', $error);
        $clear = $this->redis->clearLastError();
        $this->assertTrue($clear);
    }


    /** @test */
    public function redis_Scripting_eval()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $this->assertEquals(1, $this->redis->eval('return 1'));
    }
}
