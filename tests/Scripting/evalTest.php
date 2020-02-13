<?php

namespace Webdcg\Redis\Tests;

use PHPUnit\Framework\TestCase;
use Webdcg\Redis\Redis;

class evalTest extends TestCase
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
        $this->key = 'Scripting:eval';
        $this->keyOptional = 'Scripting:eval:Optional';
    }

    /*
     * ========================================================================
     * eval
     *
     * Redis | Scripting | eval => Evaluate a LUA script serverside.
     * ========================================================================
     */

    /** @test */
    public function redis_Scripting_eval_simple()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $this->assertEquals(1, $this->redis->eval('return 1'));
    }

    /** @test */
    public function redis_Scripting_eval_array()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $this->assertEquals([1, 2, 3], $this->redis->eval('return {1,2,3}'));
    }

    /** @test */
    public function redis_Scripting_eval_lrange()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $this->assertEquals(1, $this->redis->rPush($this->key, 'a'));
        $this->assertEquals(2, $this->redis->rPush($this->key, 'b'));
        $this->assertEquals(3, $this->redis->rPush($this->key, 'c'));
        $script = "return redis.call('lrange', '{$this->key}', 0, -1)";
        $this->assertEquals(['a', 'b', 'c'], $this->redis->eval($script));
    }
}
