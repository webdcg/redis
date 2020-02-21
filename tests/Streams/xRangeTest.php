<?php

namespace Webdcg\Redis\Tests;

use PHPUnit\Framework\TestCase;
use Webdcg\Redis\Redis;

class xRangeTest extends TestCase
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
        $this->key = 'Streams:xRangeTest';
        $this->keyOptional = $this->key . ':Optional';
        $this->group = $this->key . ':Group';
    }

    protected function tearDown(): void
    {
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
    }


    /*
     * ========================================================================
     * xRange
     *
     * Redis | Streams | xRange => Get a range of messages from a given stream.
     * ========================================================================
     */


    /** @test */
    public function redis_streams_xRange_single()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $expected = (int) floor(microtime(true) * 1000) - 1;
        $messageId = $this->redis->xAdd($this->key, '*', ['key' => 'value']);
        $this->assertGreaterThanOrEqual($expected, explode('-', $messageId)[0]);
        $xRange = $this->redis->xRange($this->key, '-', '+');
        $range = [ $messageId => ['key' => 'value'] ];
        $this->assertIsIterable($xRange);
        $this->assertIsArray($xRange);
        $this->assertEquals($range, $xRange);
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
    }


    /** @test */
    public function redis_streams_xRange_multiple()
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
        $xRange = $this->redis->xRange($this->key, '-', '+');
        $this->assertIsIterable($xRange);
        $this->assertIsArray($xRange);
        $this->assertEquals($messages, $xRange);
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
    }


    /** @test */
    public function redis_streams_xRange_count()
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
        $xRange = $this->redis->xRange($this->key, '-', '+', 5);
        $this->assertIsIterable($xRange);
        $this->assertIsArray($xRange);
        $this->assertEquals(array_slice($messages, 0, 5, true), $xRange);
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
    }
}
