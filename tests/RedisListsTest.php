<?php

namespace Webdcg\Redis\Tests;

use PHPUnit\Framework\TestCase;
use Webdcg\Redis\Redis;

class RedisListsTest extends TestCase
{
    protected $redis;
    protected $key;

    protected function setUp(): void
    {
        $this->redis = new Redis();
        $this->redis->connect();
        $this->redis->setOption(\Redis::OPT_SERIALIZER, \Redis::SERIALIZER_NONE);
        $this->key = 'Lists';
    }

    /** @test */
    public function redis_lists_lremove_remove_b_one()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $this->assertEquals(1, $this->redis->lPush($this->key, "A"));
        $this->assertEquals(2, $this->redis->lPush($this->key, "B"));
        $this->assertEquals(3, $this->redis->lPush($this->key, "C"));
        $this->assertEquals(4, $this->redis->lPush($this->key, "B"));
        $this->assertEquals(5, $this->redis->lPush($this->key, "A"));
        $this->assertEquals(6, $this->redis->lPush($this->key, "A"));
        // --------------------  T E S T  --------------------
        $this->assertEquals(1, $this->redis->lRemove($this->key, 'B', 1));
        $this->assertEquals(['A', 'A', 'C', 'B', 'A'], $this->redis->lGetRange($this->key));
        // Cleanup used keys
        $this->assertEquals(1, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_lists_lremove_remove_b_all()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $this->assertEquals(1, $this->redis->lPush($this->key, "A"));
        $this->assertEquals(2, $this->redis->lPush($this->key, "B"));
        $this->assertEquals(3, $this->redis->lPush($this->key, "C"));
        $this->assertEquals(4, $this->redis->lPush($this->key, "B"));
        $this->assertEquals(5, $this->redis->lPush($this->key, "A"));
        $this->assertEquals(6, $this->redis->lPush($this->key, "A"));
        // --------------------  T E S T  --------------------
        $this->assertEquals(2, $this->redis->lRemove($this->key, 'B'));
        $this->assertEquals(['A', 'A', 'C', 'A'], $this->redis->lGetRange($this->key));
        // Cleanup used keys
        $this->assertEquals(1, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_lists_lremove_remove_c()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $this->assertEquals(1, $this->redis->lPush($this->key, "A"));
        $this->assertEquals(2, $this->redis->lPush($this->key, "B"));
        $this->assertEquals(3, $this->redis->lPush($this->key, "C"));
        $this->assertEquals(4, $this->redis->lPush($this->key, "B"));
        $this->assertEquals(5, $this->redis->lPush($this->key, "A"));
        $this->assertEquals(6, $this->redis->lPush($this->key, "A"));
        // --------------------  T E S T  --------------------
        $this->assertEquals(0, $this->redis->lRemove($this->key, 'D'));
        $this->assertEquals(1, $this->redis->lRemove($this->key, 'C'));
        $this->assertEquals(['A', 'A', 'B', 'B', 'A'], $this->redis->lGetRange($this->key));
        // Cleanup used keys
        $this->assertEquals(1, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_lists_lremove_remove_d()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $this->assertEquals(1, $this->redis->lPush($this->key, "A"));
        $this->assertEquals(2, $this->redis->lPush($this->key, "B"));
        $this->assertEquals(3, $this->redis->lPush($this->key, "C"));
        $this->assertEquals(4, $this->redis->lPush($this->key, "B"));
        $this->assertEquals(5, $this->redis->lPush($this->key, "A"));
        $this->assertEquals(6, $this->redis->lPush($this->key, "A"));
        // --------------------  T E S T  --------------------
        $this->assertEquals(0, $this->redis->lRemove($this->key, 'D'));
        $this->assertEquals(['A', 'A', 'B', 'C', 'B', 'A'], $this->redis->lGetRange($this->key));
        // Cleanup used keys
        $this->assertEquals(1, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_lists_lrem_remove_b_one()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $this->assertEquals(1, $this->redis->lPush($this->key, "A"));
        $this->assertEquals(2, $this->redis->lPush($this->key, "B"));
        $this->assertEquals(3, $this->redis->lPush($this->key, "C"));
        $this->assertEquals(4, $this->redis->lPush($this->key, "B"));
        $this->assertEquals(5, $this->redis->lPush($this->key, "A"));
        $this->assertEquals(6, $this->redis->lPush($this->key, "A"));
        // --------------------  T E S T  --------------------
        $this->assertEquals(1, $this->redis->lRem($this->key, 'B', 1));
        $this->assertEquals(['A', 'A', 'C', 'B', 'A'], $this->redis->lGetRange($this->key));
        // Cleanup used keys
        $this->assertEquals(1, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_lists_lrem_remove_b_all()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $this->assertEquals(1, $this->redis->lPush($this->key, "A"));
        $this->assertEquals(2, $this->redis->lPush($this->key, "B"));
        $this->assertEquals(3, $this->redis->lPush($this->key, "C"));
        $this->assertEquals(4, $this->redis->lPush($this->key, "B"));
        $this->assertEquals(5, $this->redis->lPush($this->key, "A"));
        $this->assertEquals(6, $this->redis->lPush($this->key, "A"));
        // --------------------  T E S T  --------------------
        $this->assertEquals(2, $this->redis->lRem($this->key, 'B'));
        $this->assertEquals(['A', 'A', 'C', 'A'], $this->redis->lGetRange($this->key));
        // Cleanup used keys
        $this->assertEquals(1, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_lists_lrem_remove_c()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $this->assertEquals(1, $this->redis->lPush($this->key, "A"));
        $this->assertEquals(2, $this->redis->lPush($this->key, "B"));
        $this->assertEquals(3, $this->redis->lPush($this->key, "C"));
        $this->assertEquals(4, $this->redis->lPush($this->key, "B"));
        $this->assertEquals(5, $this->redis->lPush($this->key, "A"));
        $this->assertEquals(6, $this->redis->lPush($this->key, "A"));
        // --------------------  T E S T  --------------------
        $this->assertEquals(0, $this->redis->lRem($this->key, 'D'));
        $this->assertEquals(1, $this->redis->lRem($this->key, 'C'));
        $this->assertEquals(['A', 'A', 'B', 'B', 'A'], $this->redis->lGetRange($this->key));
        // Cleanup used keys
        $this->assertEquals(1, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_lists_lrem_remove_d()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $this->assertEquals(1, $this->redis->lPush($this->key, "A"));
        $this->assertEquals(2, $this->redis->lPush($this->key, "B"));
        $this->assertEquals(3, $this->redis->lPush($this->key, "C"));
        $this->assertEquals(4, $this->redis->lPush($this->key, "B"));
        $this->assertEquals(5, $this->redis->lPush($this->key, "A"));
        $this->assertEquals(6, $this->redis->lPush($this->key, "A"));
        // --------------------  T E S T  --------------------
        $this->assertEquals(0, $this->redis->lRem($this->key, 'D'));
        $this->assertEquals(['A', 'A', 'B', 'C', 'B', 'A'], $this->redis->lGetRange($this->key));
        // Cleanup used keys
        $this->assertEquals(1, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_lists_lgetrange()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $this->assertEquals(1, $this->redis->lPush($this->key, "A"));
        $this->assertEquals(2, $this->redis->lPush($this->key, "B"));
        $this->assertEquals(3, $this->redis->lPush($this->key, "C"));
        // --------------------  T E S T  --------------------
        $this->assertEquals(['C', 'B', 'A'], $this->redis->lGetRange($this->key));
        $this->assertEquals(['C', 'B', 'A'], $this->redis->lGetRange($this->key, 0, -1));
        $this->assertEquals(['C', 'B'], $this->redis->lGetRange($this->key, 0, -2));
        $this->assertEquals(['B', 'A'], $this->redis->lGetRange($this->key, 1));
        $this->assertEquals(['B', 'A'], $this->redis->lGetRange($this->key, 1, -1));
        // Cleanup used keys
        $this->assertEquals(1, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_lists_lrange()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $this->assertEquals(1, $this->redis->lPush($this->key, "A"));
        $this->assertEquals(2, $this->redis->lPush($this->key, "B"));
        $this->assertEquals(3, $this->redis->lPush($this->key, "C"));
        // --------------------  T E S T  --------------------
        $this->assertEquals(['C', 'B', 'A'], $this->redis->lRange($this->key));
        $this->assertEquals(['C', 'B', 'A'], $this->redis->lRange($this->key, 0, -1));
        $this->assertEquals(['C', 'B'], $this->redis->lRange($this->key, 0, -2));
        $this->assertEquals(['B', 'A'], $this->redis->lRange($this->key, 1));
        $this->assertEquals(['B', 'A'], $this->redis->lRange($this->key, 1, -1));
        // Cleanup used keys
        $this->assertEquals(1, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_lists_lpushx_float()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        // --------------------  T E S T  --------------------
        $this->assertEquals(0, $this->redis->lPushx($this->key, 1.1));
        $this->assertEquals(1, $this->redis->lPush($this->key, 1.1));
        $this->assertEquals(2, $this->redis->lPush($this->key, 2.2));
        $this->assertEquals(3, $this->redis->lPush($this->key, 3.3));
        // Cleanup used keys
        $this->assertEquals(1, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_lists_lpushx_int()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        // --------------------  T E S T  --------------------
        $this->assertEquals(0, $this->redis->lPushx($this->key, 11));
        $this->assertEquals(1, $this->redis->lPush($this->key, 11));
        $this->assertEquals(2, $this->redis->lPush($this->key, 22));
        $this->assertEquals(3, $this->redis->lPush($this->key, 33));
        // Cleanup used keys
        $this->assertEquals(1, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_lists_lpushx_string()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        // --------------------  T E S T  --------------------
        $this->assertEquals(0, $this->redis->lPushx($this->key, "A"));
        $this->assertEquals(1, $this->redis->lPush($this->key, "A"));
        $this->assertEquals(2, $this->redis->lPush($this->key, "B"));
        $this->assertEquals(3, $this->redis->lPush($this->key, "C"));
        // Cleanup used keys
        $this->assertEquals(1, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_lists_lpush_float()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        for ($i = 1; $i <= 10; $i++) {
            // --------------------  T E S T  --------------------
            $this->assertEquals($i, $this->redis->lPush($this->key, $i * 1.1));
        }
        // Cleanup used keys
        $this->assertEquals(1, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_lists_lpush_int()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        for ($i = 1; $i <= 10; $i++) {
            // --------------------  T E S T  --------------------
            $this->assertEquals($i, $this->redis->lPush($this->key, $i));
        }
        // Cleanup used keys
        $this->assertEquals(1, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_lists_lpush_string()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        for ($i = 1; $i <= 10; $i++) {
            // --------------------  T E S T  --------------------
            $this->assertEquals($i, $this->redis->lPush($this->key, "Item{$i}"));
        }
        // Cleanup used keys
        $this->assertEquals(1, $this->redis->delete($this->key));
    }
}
