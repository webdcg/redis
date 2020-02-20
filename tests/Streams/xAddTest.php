<?php

namespace Webdcg\Redis\Tests;

use PHPUnit\Framework\TestCase;
use Webdcg\Redis\Redis;

class xAddTest extends TestCase
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
        $this->key = 'Streams:xAddTest';
        $this->keyOptional = 'Streams:xAddTest:Optional';
    }


    /*
     * ========================================================================
     * xAdd
     *
     * Redis | Sorted Sets | xAdd => Appends the specified stream entry to the stream at the specified key.
     * ========================================================================
     */
    

    /** @test */
    public function redis_streams_xadd_simple()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $messageId = $this->redis->xAdd($this->key, '*', ['key' => 'value']);
        $expected = (int) floor(microtime(true) * 1000) - 15;
        $this->assertGreaterThanOrEqual($expected, explode('-', $messageId)[0]);
        $this->assertEquals(1, $this->redis->delete($this->key));
    }
}
