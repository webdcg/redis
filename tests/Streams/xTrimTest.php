<?php

namespace Webdcg\Redis\Tests;

use PHPUnit\Framework\TestCase;
use Webdcg\Redis\Redis;

class xTrimTest extends TestCase
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
        $this->key = 'Streams:xTrimTest';
        $this->keyOptional = $this->key . ':Optional';
        $this->group = $this->key . ':Group';
    }

    protected function tearDown(): void
    {
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
    }


    /*
     * ========================================================================
     * xTrim
     *
     * Redis | Streams | xTrim => Trim the stream length to a given maximum. If the "approximate" flag is pasesed, Redis will use your size as a hint but only trim trees in whole nodes (this is more efficient).
     * ========================================================================
     */


    /** @test */
    public function redis_streams_xTrim_exact()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $total = random_int(10, 20);
        $trim = random_int(1, 5);
        
        for ($i = 0; $i < $total; $i++) {
            $this->redis->xAdd($this->key, '*', ['key' => $i]);
        }
        
        $xLen = $this->redis->xLen($this->key);
        $this->assertIsScalar($xLen);
        $this->assertIsNumeric($xLen);
        $this->assertIsInt($xLen);
        $this->assertEquals($total, $xLen);

        $xTrim = $this->redis->xTrim($this->key, $trim);
        $this->assertIsScalar($xTrim);
        $this->assertIsNumeric($xTrim);
        $this->assertIsInt($xTrim);
        $this->assertEquals($total - $trim, $xTrim);

        $xLen = $this->redis->xLen($this->key);
        $this->assertIsScalar($xLen);
        $this->assertIsNumeric($xLen);
        $this->assertIsInt($xLen);
        $this->assertEquals($total - $xTrim, $xLen);

        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_streams_xTrim_approximate()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $total = random_int(999, 1099);
        $trim = random_int(1, 5);
        
        for ($i = 0; $i < $total; $i++) {
            $this->redis->xAdd($this->key, '*', ['key' => $i]);
        }
        
        $xLen = $this->redis->xLen($this->key);
        $this->assertIsScalar($xLen);
        $this->assertIsNumeric($xLen);
        $this->assertIsInt($xLen);
        $this->assertEquals($total, $xLen);

        $xTrim = $this->redis->xTrim($this->key, $trim, true);

        $this->assertIsScalar($xTrim);
        $this->assertIsNumeric($xTrim);
        $this->assertIsInt($xTrim);
        // $this->assertGreaterThanOrEqual($total - $trim, $xTrim);

        $xLen = $this->redis->xLen($this->key);
        $this->assertIsScalar($xLen);
        $this->assertIsNumeric($xLen);
        $this->assertIsInt($xLen);
        $this->assertEquals($total - $xTrim, $xLen);

        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
    }
}
