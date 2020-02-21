<?php

namespace Webdcg\Redis\Tests;

use PHPUnit\Framework\TestCase;
use Webdcg\Redis\Redis;

class xRevRangeTest extends TestCase
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
        $this->key = 'Streams:xRevRangeTest';
        $this->keyOptional = $this->key . ':Optional';
        $this->group = $this->key . ':Group';
    }

    protected function tearDown(): void
    {
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
    }


    /*
     * ========================================================================
     * xRevRange
     *
     * Redis | Streams | xRevRange => Get a range of messages from a given stream.
     * ========================================================================
     */


    /** @test */
    public function redis_streams_xRevRange_single()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $expected = (int) floor(microtime(true) * 1000) - 1;
        $messageId = $this->redis->xAdd($this->key, '*', ['key' => 'value']);
        $this->assertGreaterThanOrEqual($expected, explode('-', $messageId)[0]);
        $xRevRange = $this->redis->xRevRange($this->key, '+', '-');
        $range = [ $messageId => ['key' => 'value'] ];
        $this->assertIsIterable($xRevRange);
        $this->assertIsArray($xRevRange);
        $this->assertEquals($range, $xRevRange);
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
    }


    /** @test */
    public function redis_streams_xRevRange_multiple()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $expected = (int) floor(microtime(true) * 1000) - 1;
        $total = random_int(1, 10);
        for ($i = 0; $i < $total; $i++) {
            $messageId = $this->redis->xAdd($this->key, '*', ['key' => $i]);
            $messages[$messageId] = ['key' => $i];
            $this->assertGreaterThanOrEqual($expected, explode('-', $messageId)[0]);
        }
        $xRevRange = $this->redis->xRevRange($this->key, '+', '-');
        $this->assertIsIterable($xRevRange);
        $this->assertIsArray($xRevRange);
        $this->assertEquals($messages, $xRevRange);
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
    }


    /** @test */
    public function redis_streams_xRevRange_count()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $expected = (int) floor(microtime(true) * 1000) - 1;
        $total = random_int(6, 10);

        for ($i = 0; $i < $total; $i++) {
            $messageId = $this->redis->xAdd($this->key, '*', ['key' => $i]);
            $messages[$messageId] = ['key' => $i];
            $this->assertGreaterThanOrEqual($expected, explode('-', $messageId)[0]);
        }

        $xRevRange = $this->redis->xRevRange($this->key, '+', '-', 2);
        $slice = array_slice($messages, -2, 2, true);

        $this->assertIsIterable($xRevRange);
        $this->assertIsArray($xRevRange);
        $this->assertEquals($slice, $xRevRange);
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
    }
}
