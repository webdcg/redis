<?php

namespace Webdcg\Redis\Tests;

use PHPUnit\Framework\TestCase;
use Webdcg\Redis\Redis;

class xAckTest extends TestCase
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
        $this->key = 'Streams:xAckTest';
        $this->keyOptional = $this->key . ':Optional';
        $this->group = $this->key . ':Group';
    }


    /*
     * ========================================================================
     * xAck
     *
     * Redis | Streams | xAck => Acknowledge one or more pending messages.
     * ========================================================================
     */


    /** @test */
    public function redis_streams_xAck_simple()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $expected = (int) floor(microtime(true) * 1000) - 1;
        $messageId = $this->redis->xAdd($this->key, '*', ['key' => 'value']);
        $this->assertGreaterThanOrEqual($expected, explode('-', $messageId)[0]);
        $start = $expected . '-0';
        $end = ($expected + 10) . '-10';
        $this->assertTrue($this->redis->xGroup('CREATE', $this->key, $this->group, 0, true));
        $this->assertEquals(0, $this->redis->xAck($this->key, $this->group, [$start, $messageId, $end]));
        $this->assertEquals(1, $this->redis->delete($this->key));
    }
}
