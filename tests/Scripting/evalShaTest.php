<?php

namespace Webdcg\Redis\Tests;

use PHPUnit\Framework\TestCase;
use Webdcg\Redis\Exceptions\ScriptCommandException;
use Webdcg\Redis\Redis;

class evalShaTest extends TestCase
{
    protected $redis;
    protected $key;
    protected $keyOptional;
    protected $producer;
    protected $sha1;

    protected $copyKey = <<<EOF
local s = KEYS[1]
local d = KEYS[2] 
redis.call("RESTORE", d, 0, redis.call("DUMP", s))
return {"OK"}
EOF;

    protected $slowCopyKey = <<<EOF
local s = KEYS[1]
local d = KEYS[2] 
redis.call("RESTORE", d, 0, redis.call("DUMP", s))
return {"OK"}
EOF;

    protected function setUp(): void
    {
        $this->redis = new Redis();
        $this->redis->connect();
        $this->redis->setOption(\Redis::OPT_SERIALIZER, \Redis::SERIALIZER_NONE);
        $this->key = 'Scripting:evalSha';
        $this->keyOptional = 'Scripting:evalSha:Optional';
    }


    /*
     * ========================================================================
     * script
     *
     * Redis | Scripting | evalSha =>  Evaluate a LUA script serverside, from the SHA1 hash of the script instead of the script itself.
     * ========================================================================
     */


    /** @test */
    public function redis_Scripting_evalSha_load_exception()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $this->sha1 = sha1($this->copyKey);
        $this->expectException(ScriptCommandException::class);
        $this->assertEquals($this->sha1, $this->redis->script('load', $this->copyKey, $this->key));
        $this->assertTrue($this->redis->script('flush'));
    }


    /** @test */
    public function redis_Scripting_evalSha_load_run()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->keyOptional));
        $this->sha1 = sha1($this->copyKey);
        $this->assertEquals($this->sha1, $this->redis->script('load', $this->copyKey));
        $this->assertTrue($this->redis->set($this->key, 'value'));
        $this->assertEquals('value', $this->redis->get($this->key));
        $this->assertEquals(['OK'], $this->redis->evalSha($this->sha1, [$this->key, $this->keyOptional], 2));
        $this->assertEquals('value', $this->redis->get($this->keyOptional));
        // Start from scratch
        $this->assertTrue($this->redis->script('flush'));
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->keyOptional));
    }

    /** @test */
    public function redis_Scripting_evalSha_load_simple()
    {
        $this->copyKey = "return 1";
        $this->sha1 = sha1($this->copyKey);
        $this->assertEquals($this->sha1, $this->redis->script('load', $this->copyKey));
        $this->assertEquals(1, $this->redis->evalSha($this->sha1));
        // Start from scratch
        $this->assertTrue($this->redis->script('flush'));
    }
}
