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
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete('HyperLogLog'));
        $this->assertEquals(1, $this->redis->pfAdd('HyperLogLog', ['a', 'b', 'c']));
        $this->assertEquals(0, $this->redis->pfAdd('HyperLogLog', ['a', 'c']));
        // Cleanup used keys
        $this->assertGreaterThanOrEqual(0, $this->redis->delete('HyperLogLog'));
    }

    /** @test */
    public function redis_hyperloglogs_pfadd_dedupe()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete('HyperLogLog'));
        $this->assertEquals(1, $this->redis->pfAdd('HyperLogLog', ['a', 'b', 'c']));
        $this->assertEquals(1, $this->redis->pfAdd('HyperLogLog', ['b', 'd']));
        $this->assertEquals(0, $this->redis->pfAdd('HyperLogLog', ['a', 'a', 'b']));
        // Cleanup used keys
        $this->assertGreaterThanOrEqual(0, $this->redis->delete('HyperLogLog'));
    }

    /** @test */
    public function redis_hyperloglogs_pfcount()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete('HyperLogLog'));
        // Add 3 unique elements to the HyperLogLog
        $this->assertEquals(1, $this->redis->pfAdd('HyperLogLog', ['a', 'b', 'c']));
        // Should return 3
        $this->assertEquals(3, $this->redis->pfCount('HyperLogLog'));
        // Add one more different item to the HyperLogLog as well as one repeated item.
        $this->assertEquals(1, $this->redis->pfAdd('HyperLogLog', ['b', 'd']));
        // Should return 4
        $this->assertEquals(4, $this->redis->pfCount('HyperLogLog'));
        // Cleanup used keys
        $this->assertGreaterThanOrEqual(0, $this->redis->delete('HyperLogLog'));
    }

    /** @test */
    public function redis_hyperloglogs_pfcount_array()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete(['HyperLogLog', 'HyperLogLog2']));
        // Add 3 unique elements to the HyperLogLog
        $this->assertEquals(1, $this->redis->pfAdd('HyperLogLog', ['a', 'b', 'c']));
        // Should return 3
        $this->assertEquals(3, $this->redis->pfCount('HyperLogLog'));
        // Add one more different item to the HyperLogLog as well as one repeated item.
        $this->assertEquals(1, $this->redis->pfAdd('HyperLogLog2', ['b', 'd']));
        // Should return 4
        $this->assertEquals(4, $this->redis->pfCount(['HyperLogLog', 'HyperLogLog2']));
        // Cleanup used keys
        $this->assertGreaterThanOrEqual(0, $this->redis->delete(['HyperLogLog', 'HyperLogLog2']));
    }

    /** @test */
    public function redis_hyperloglogs_pfcount_thousands()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete('HyperLogLog'));

        // Generate between 1 and 2 thousand elements
        $total = random_int(999, 1999);
        for ($i = 0; $i < $total; $i++) {
            $this->assertGreaterThanOrEqual(0, $this->redis->pfAdd('HyperLogLog', [$i]));
        }

        // Get the HyperLogLog Count and expect 95% certainty
        $count = $this->redis->pfCount('HyperLogLog');
        $this->assertGreaterThanOrEqual($total * 0.95, $count);

        // Cleanup used keys
        $this->assertGreaterThanOrEqual(0, $this->redis->delete('HyperLogLog'));
    }
}
