<?php

namespace Webdcg\Redis\Tests;

use PHPUnit\Framework\TestCase;
use Webdcg\Redis\Redis;

class xClaimTest extends TestCase
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
        $this->key = 'Streams:xClaimTest';
        $this->keyOptional = 'Streams:xClaimTest:Optional';
        $this->group = $this->key.':Group';
    }


    /*
     * ========================================================================
     * xClaim
     *
     * Redis | Sorted Sets | xClaim => Claim ownership of one or more pending messages.
     * ========================================================================
     */


    /** @test */
    public function redis_streams_xClaim_simple()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $messageId = $this->redis->xAdd($this->key, '*', ['key' => 'value']);
        $expected = (int) floor(microtime(true) * 1000) - 15;
        $this->assertGreaterThanOrEqual($expected, explode('-', $messageId)[0]);
        $start = $expected.'-0';
        $end = ($expected + 10).'-10';
        $this->assertTrue($this->redis->xGroup('CREATE', $this->key, $this->group, 0, true));

        $this->assertEquals([], $this->redis->xClaim(
            $this->key,
            $this->group,
            'consumer',
            0,
            [$start, $end]
        ));

        $this->assertEquals([], $this->redis->xClaim(
            $this->key,
            $this->group,
            'consumer',
            0,
            [$start, $end],
            [
                'IDLE' => time() * 1000,
                'RETRYCOUNT' => 5,
                'FORCE',
                'JUSTID'
            ]
        ));
        $this->assertEquals(1, $this->redis->delete($this->key));
    }
}
