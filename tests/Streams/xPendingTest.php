<?php

namespace Webdcg\Redis\Tests;

use PHPUnit\Framework\TestCase;
use Webdcg\Redis\Redis;

class xPendingTest extends TestCase
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
        $this->key = 'Streams:xPendingTest';
        $this->keyOptional = $this->key . ':Optional';
        $this->group = $this->key . ':Group';
    }

    protected function tearDown(): void
    {
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
    }


    /*
     * ========================================================================
     * xPending
     *
     * Redis | Sorted Sets | xPending => Get information about pending messages in a given stream.
     * ========================================================================
     */


    /** @test */
    public function redis_streams_xPending_simple()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $expected = (int) floor(microtime(true) * 1000) - 1;
        $messageId = $this->redis->xAdd($this->key, '*', ['key' => 'value']);
        $this->assertGreaterThanOrEqual($expected, explode('-', $messageId)[0]);

        /*
        $start = $expected . '-0';
        $end = ($expected + 10) . '-10';
        $messageIds = [$start, $messageId, $end];
        $xPending = $this->redis->xPending($this->key, $messageIds);
        $this->assertIsScalar($xPending);
        $this->assertIsNumeric($xPending);
        $this->assertIsInt($xPending);
        $this->assertEquals(1, $xPending);
        */
    }
}
