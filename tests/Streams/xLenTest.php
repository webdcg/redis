<?php

namespace Webdcg\Redis\Tests;

use PHPUnit\Framework\TestCase;
use Webdcg\Redis\Redis;

class xLenTest extends TestCase
{
    protected $redis;
    protected $key;
    protected $keyOptional;
    protected $group;
    protected $producer;

    protected function setUp(): void
    {
        $this->redis = new Redis();
        $this->redis->connect();
        $this->redis->setOption(\Redis::OPT_SERIALIZER, \Redis::SERIALIZER_NONE);
        $this->key = 'Streams:xLenTest';
        $this->keyOptional = $this->key . ':Optional';
        $this->group = $this->key . ':Group';
    }

    protected function tearDown(): void
    {
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
    }


    /*
     * ========================================================================
     * xLen
     *
     * Redis | Streams | xLen => Get the length of a given stream.
     * ========================================================================
     */


    /** @test */
    public function redis_streams_xLen_single()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $expected = (int) floor(microtime(true) * 1000) - 1;
        $messageId = $this->redis->xAdd($this->key, '*', ['key' => 'value']);
        $this->assertGreaterThanOrEqual($expected, explode('-', $messageId)[0]);
        $xLen = $this->redis->xLen($this->key);
        $this->assertIsScalar($xLen);
        $this->assertIsNumeric($xLen);
        $this->assertIsInt($xLen);
        $this->assertEquals(1, $xLen);
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_streams_xLen_multiple()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $expected = (int) floor(microtime(true) * 1000) - 1;
        $total = random_int(1, 10);
        for ($i = 0; $i < $total; $i++) {
            $messageId = $this->redis->xAdd($this->key, '*', ['key' => $i]);
            $this->assertGreaterThanOrEqual($expected, explode('-', $messageId)[0]);
        }
        $xLen = $this->redis->xLen($this->key);
        $this->assertIsScalar($xLen);
        $this->assertIsNumeric($xLen);
        $this->assertIsInt($xLen);
        $this->assertEquals($total, $xLen);
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
    }
}
