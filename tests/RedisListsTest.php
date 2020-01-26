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
    public function redis_lists_lsize()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        for ($i = 1; $i <= 10; $i++) {
            // --------------------  T E S T  --------------------
            $this->assertEquals($i, $this->redis->rPush($this->key, $i));
        }
        // --------------------  T E S T  --------------------
        $this->assertEquals(10, $this->redis->lSize($this->key));
        // Cleanup used keys
        $this->assertEquals(1, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_lists_llen()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        for ($i = 1; $i <= 10; $i++) {
            // --------------------  T E S T  --------------------
            $this->assertEquals($i, $this->redis->rPush($this->key, $i));
        }
        // --------------------  T E S T  --------------------
        $this->assertEquals(10, $this->redis->lLen($this->key));
        // Cleanup used keys
        $this->assertEquals(1, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_lists_rpushx_float()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        // --------------------  T E S T  --------------------
        $this->assertEquals(0, $this->redis->rPushx($this->key, 1.1));
        $this->assertEquals(1, $this->redis->rPush($this->key, 1.1));
        $this->assertEquals(2, $this->redis->rPushx($this->key, 2.2));
        $this->assertEquals(3, $this->redis->rPush($this->key, 3.3));
        // Cleanup used keys
        $this->assertEquals(1, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_lists_rpushx_int()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        // --------------------  T E S T  --------------------
        $this->assertEquals(0, $this->redis->rPushx($this->key, 11));
        $this->assertEquals(1, $this->redis->rPush($this->key, 11));
        $this->assertEquals(2, $this->redis->rPushx($this->key, 22));
        $this->assertEquals(3, $this->redis->rPush($this->key, 33));
        // Cleanup used keys
        $this->assertEquals(1, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_lists_rpushx_string()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        // --------------------  T E S T  --------------------
        $this->assertEquals(0, $this->redis->rPushx($this->key, "A"));
        $this->assertEquals(1, $this->redis->rPush($this->key, "A"));
        $this->assertEquals(2, $this->redis->rPushx($this->key, "B"));
        $this->assertEquals(3, $this->redis->rPush($this->key, "C"));
        // Cleanup used keys
        $this->assertEquals(1, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_lists_rpush_float()
    {
        $items = [];
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        for ($i = 1; $i <= 10; $i++) {
            // --------------------  T E S T  --------------------
            $this->assertEquals($i, $this->redis->rPush($this->key, $i * 1.1));
            $items[] = $i * 1.1;
        }
        // --------------------  T E S T  --------------------
        $this->assertEquals($items, $this->redis->lGetRange($this->key));
        // Cleanup used keys
        $this->assertEquals(1, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_lists_rpush_int()
    {
        $items = [];
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        for ($i = 1; $i <= 10; $i++) {
            // --------------------  T E S T  --------------------
            $this->assertEquals($i, $this->redis->rPush($this->key, $i));
            $items[] = $i;
        }
        // --------------------  T E S T  --------------------
        $this->assertEquals($items, $this->redis->lGetRange($this->key));
        // Cleanup used keys
        $this->assertEquals(1, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_lists_rpush_string()
    {
        $items = [];
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        for ($i = 1; $i <= 10; $i++) {
            // --------------------  T E S T  --------------------
            $this->assertEquals($i, $this->redis->rPush($this->key, "Item{$i}"));
            $items[] = "Item{$i}";
        }
        // --------------------  T E S T  --------------------
        $this->assertEquals($items, $this->redis->lGetRange($this->key));
        // Cleanup used keys
        $this->assertEquals(1, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_lists_listtrim_keep_middle()
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
        $this->assertTrue($this->redis->listTrim($this->key, 1, -2));
        $this->assertEquals(['A', 'B', 'C', 'B'], $this->redis->lGetRange($this->key));
        // Cleanup used keys
        $this->assertEquals(1, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_lists_listtrim_keep_head()
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
        $this->assertTrue($this->redis->listTrim($this->key, -2, -1));
        $this->assertEquals(['B', 'A'], $this->redis->lGetRange($this->key));
        // Cleanup used keys
        $this->assertEquals(1, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_lists_listtrim_keep_tail()
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
        $this->assertTrue($this->redis->listTrim($this->key, 0, 1));
        $this->assertEquals(['A', 'A'], $this->redis->lGetRange($this->key));
        // Cleanup used keys
        $this->assertEquals(1, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_lists_ltrim_keep_middle()
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
        $this->assertTrue($this->redis->lTrim($this->key, 1, -2));
        $this->assertEquals(['A', 'B', 'C', 'B'], $this->redis->lGetRange($this->key));
        // Cleanup used keys
        $this->assertEquals(1, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_lists_ltrim_keep_head()
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
        $this->assertTrue($this->redis->lTrim($this->key, -2, -1));
        $this->assertEquals(['B', 'A'], $this->redis->lGetRange($this->key));
        // Cleanup used keys
        $this->assertEquals(1, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_lists_ltrim_keep_tail()
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
        $this->assertTrue($this->redis->lTrim($this->key, 0, 1));
        $this->assertEquals(['A', 'A'], $this->redis->lGetRange($this->key));
        // Cleanup used keys
        $this->assertEquals(1, $this->redis->delete($this->key));
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
        $this->assertEquals(2, $this->redis->lPushx($this->key, 2.2));
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
        $this->assertEquals(2, $this->redis->lPushx($this->key, 22));
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
        $this->assertEquals(2, $this->redis->lPushx($this->key, "B"));
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