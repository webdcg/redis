<?php

namespace Webdcg\Redis\Tests;

use PHPUnit\Framework\TestCase;
use Webdcg\Redis\Redis;

class RedisHyperLogLogsTest extends TestCase
{
    protected $redis;
    protected $key;

    protected function setUp(): void
    {
        $this->redis = new Redis();
        $this->redis->connect();
        $this->key = 'HyperLogLog';
        $this->keyOptional = 'HyperLogLogOptional';
    }

    /** @test */
    public function redis_hyperloglogs_pfadd()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $this->assertEquals(1, $this->redis->pfAdd($this->key, ['a', 'b', 'c']));
        $this->assertEquals(0, $this->redis->pfAdd($this->key, ['a', 'c']));
        // Cleanup used keys
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_hyperloglogs_pfadd_dedupe()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $this->assertEquals(1, $this->redis->pfAdd($this->key, ['a', 'b', 'c']));
        $this->assertEquals(1, $this->redis->pfAdd($this->key, ['b', 'd']));
        $this->assertEquals(0, $this->redis->pfAdd($this->key, ['a', 'a', 'b']));
        // Cleanup used keys
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_hyperloglogs_pfcount()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        // Add 3 unique elements to the HyperLogLog
        $this->assertEquals(1, $this->redis->pfAdd($this->key, ['a', 'b', 'c']));
        // Should return 3
        $this->assertEquals(3, $this->redis->pfCount($this->key));
        // Add one more different item to the HyperLogLog as well as one repeated item.
        $this->assertEquals(1, $this->redis->pfAdd($this->key, ['b', 'd']));
        // Should return 4
        $this->assertEquals(4, $this->redis->pfCount($this->key));
        // Cleanup used keys
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_hyperloglogs_pfcount_array()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete([$this->key, $this->keyOptional]));
        // Add 3 unique elements to the HyperLogLog
        $this->assertEquals(1, $this->redis->pfAdd($this->key, ['a', 'b', 'c']));
        // Should return 3
        $this->assertEquals(3, $this->redis->pfCount($this->key));
        // Add one more different item to the HyperLogLog as well as one repeated item.
        $this->assertEquals(1, $this->redis->pfAdd($this->keyOptional, ['b', 'd']));
        // Should return 4
        $this->assertEquals(4, $this->redis->pfCount([$this->key, $this->keyOptional]));
        // Cleanup used keys
        $this->assertGreaterThanOrEqual(0, $this->redis->delete([$this->key, $this->keyOptional]));
    }

    /** @test */
    public function redis_hyperloglogs_pfcount_thousands()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));

        // Generate between 1 and 1.1 thousand elements
        $total = random_int(999, 1099);
        for ($i = 0; $i < $total; $i++) {
            $this->assertGreaterThanOrEqual(0, $this->redis->pfAdd($this->key, [$i]));
        }

        // Get the HyperLogLog Count and expect 95% certainty
        $count = $this->redis->pfCount($this->key);
        $this->assertGreaterThanOrEqual($total * 0.95, $count);

        // Cleanup used keys
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_hyperloglogs_pfmerge()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete([$this->key, $this->keyOptional]));

        $this->assertEquals(1, $this->redis->pfAdd($this->key, ['a', 'b', 'c']));
        $this->assertEquals(3, $this->redis->pfCount($this->key));

        $this->assertEquals(1, $this->redis->pfAdd($this->keyOptional, ['b', 'd']));
        $this->assertEquals(2, $this->redis->pfCount($this->keyOptional));

        $this->assertTrue($this->redis->pfMerge('HyperLogLogMerged', [$this->key, $this->keyOptional]));
        $this->assertEquals(4, $this->redis->pfCount('HyperLogLogMerged'));

        // Cleanup used keys
        $this->assertGreaterThanOrEqual(0, $this->redis->delete(['HyperLogLogMerged', $this->key, $this->keyOptional]));
    }

    /** @test */
    public function redis_hyperloglogs_pfmerge_bad_merge()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete([$this->key, $this->keyOptional]));

        $this->assertEquals(1, $this->redis->pfAdd($this->key, ['a', 'b', 'c']));
        $this->assertEquals(3, $this->redis->pfCount($this->key));

        $this->assertEquals(1, $this->redis->set($this->keyOptional, $this->key));
        $this->assertEquals($this->key, $this->redis->get($this->keyOptional));

        $this->assertFalse($this->redis->pfMerge('HyperLogLogMerged', [$this->key, $this->keyOptional]));

        // Cleanup used keys
        $this->assertGreaterThanOrEqual(0, $this->redis->delete(['HyperLogLogMerged', $this->key, $this->keyOptional]));
    }
}
