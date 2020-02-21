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
        $this->keyOptional = $this->key . ':Optional';
        $this->group = $this->key . ':Group';
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
        $expected = (int) floor(microtime(true) * 1000) - 1;
        $messageId = $this->redis->xAdd($this->key, '*', ['key' => 'value']);
        $this->assertGreaterThanOrEqual($expected, explode('-', $messageId)[0]);
        $start = $expected . '-0';
        $end = ($expected + 100) . '-10';
        $this->assertTrue($this->redis->xGroup('CREATE', $this->key, $this->group, 0, true));

        // dump($start);
        // dump($messageId);
        // dump($end);

        // $messageIds = [$start, $messageId, $end];
        $messageIds = [$messageId];

        $xClaim = $this->redis->xClaim(
            $this->key,
            $this->group,
            'consumer',
            0,
            $messageIds
        );

        // dump($xClaim);

        $this->assertEquals([], $xClaim);
        $this->assertEquals(1, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_streams_xClaim_options()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $expected = (int) floor(microtime(true) * 1000) - 1;
        $messageId = $this->redis->xAdd($this->key, '*', ['key' => 'value']);
        $this->assertGreaterThanOrEqual($expected, explode('-', $messageId)[0]);
        $start = $expected . '-0';
        $end = ($expected + 100) . '-10';
        $this->assertTrue($this->redis->xGroup('CREATE', $this->key, $this->group, 0, true));

        // dump($start);
        // dump($messageId);
        // dump($end);
        // sleep(1);

        $this->assertEquals([$messageId], $this->redis->xClaim(
            $this->key,
            $this->group,
            'consumer',
            0,
            [$start, $messageId, $end],
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
