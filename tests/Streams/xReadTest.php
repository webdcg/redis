<?php

namespace Webdcg\Redis\Tests;

use PHPUnit\Framework\TestCase;
use Webdcg\Redis\Redis;

class xReadTest extends TestCase
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
        $this->key = 'Streams:xReadTest';
        $this->keyOptional = $this->key . ':Optional';
        $this->group = $this->key . ':Group';
    }

    protected function tearDown(): void
    {
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
    }


    /*
     * ========================================================================
     * xRead
     *
     * Redis | Sorted Sets | xRead => Read data from one or more streams and only return IDs greater than sent in the command.
     * ========================================================================
     */


    /** @test */
    public function redis_streams_xRead_single()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $expected = (int) floor(microtime(true) * 1000) - 1;
        $messageId = $this->redis->xAdd($this->key, '*', ['key' => 'value']);
        $this->assertGreaterThanOrEqual($expected, explode('-', $messageId)[0]);
        $xRead = $this->redis->xRead($this->key, '-', '+');
        $range = [ $messageId => ['key' => 'value'] ];
        $this->assertIsIterable($xRead);
        $this->assertIsArray($xRead);
        $this->assertEquals($range, $xRead);
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
    }


    /** @test */
    public function redis_streams_xRead_multiple()
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
        $xRead = $this->redis->xRead($this->key, '-', '+');
        $this->assertIsIterable($xRead);
        $this->assertIsArray($xRead);
        $this->assertEquals($messages, $xRead);
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
    }


    /** @test */
    public function redis_streams_xRead_count()
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
        $xRead = $this->redis->xRead($this->key, '-', '+', 5);
        $this->assertIsIterable($xRead);
        $this->assertIsArray($xRead);
        $this->assertEquals(array_slice($messages, 0, 5, true), $xRead);
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
    }
}
