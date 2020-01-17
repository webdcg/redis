<?php

namespace Webdcg\Redis\Tests;

use PHPUnit\Framework\TestCase;
use Webdcg\Redis\Redis;

class RedisHyperLogLogsTest extends TestCase
{
    protected $redis;

    protected function setUp(): void
    {
        $this->redis = new Redis;
        $this->redis->connect();
    }

    /** @test */
    public function redis_hyperloglogs_pfadd()
    {
        $this->assertGreaterThanOrEqual(0, $this->redis->delete('HyperLogLog'));
        $this->assertEquals(1, $this->redis->pfAdd('HyperLogLog', ['a', 'b', 'c']));
        $this->assertEquals(0, $this->redis->pfAdd('HyperLogLog', ['a', 'c']));
        $this->assertGreaterThanOrEqual(0, $this->redis->delete('HyperLogLog'));
    }

    /** @test */
    public function redis_hyperloglogs_pfadd_dedupe()
    {
        $this->assertGreaterThanOrEqual(0, $this->redis->delete('HyperLogLog'));
        $this->assertEquals(1, $this->redis->pfAdd('HyperLogLog', ['a', 'b', 'c']));
        $this->assertEquals(1, $this->redis->pfAdd('HyperLogLog', ['b', 'd']));
        $this->assertEquals(0, $this->redis->pfAdd('HyperLogLog', ['a', 'a', 'b']));
        $this->assertGreaterThanOrEqual(0, $this->redis->delete('HyperLogLog'));
    }
}
