<?php

namespace Webdcg\Redis\Tests;

use PHPUnit\Framework\TestCase;
use Webdcg\Redis\Redis;

class xDelTest extends TestCase
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
        $this->key = 'Streams:xDelTest';
        $this->keyOptional = $this->key.':Optional';
        $this->group = $this->key.':Group';
    }


    /*
     * ========================================================================
     * xDel
     *
     * Redis | Sorted Sets | xDel => Delete one or more messages from a stream.
     * ========================================================================
     */


    /** @test */
    public function redis_streams_xDel_simple()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $expected = (int) floor(microtime(true) * 1000) - 1;
        $messageId = $this->redis->xAdd($this->key, '*', ['key' => 'value']);
        $this->assertGreaterThanOrEqual($expected, explode('-', $messageId)[0]);
        $start = $expected.'-0';
        $end = ($expected + 10).'-10';
        $messageIds = [$start, $messageId, $end];
        $xDel = $this->redis->xDel($this->key, $messageIds);
        $this->assertIsScalar($xDel);
        $this->assertIsNumeric($xDel);
        $this->assertIsInt($xDel);
        $this->assertEquals(1, $xDel);
        $this->assertEquals(1, $this->redis->delete($this->key));
    }
}
