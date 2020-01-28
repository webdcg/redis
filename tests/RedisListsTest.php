<?php

namespace Webdcg\Redis\Tests;

use Exception;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Process\PhpProcess;
use Webdcg\Redis\Redis;

class RedisListsTest extends TestCase
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
        $this->key = 'Lists';
        $this->keyOptional = 'Lists:Optional';
    }

    /**
     * Queue Producer
     * Using the Symfony Process component, we connect to Redis and create
     * a single element on a Queue.
     * See: https://symfony.com/doc/current/index.html#gsc.tab=0.
     *
     * @return
     */
    protected function produceSingleTail()
    {
        $this->producer = new PhpProcess(
            <<<EOF
<?php
require __DIR__ . '/vendor/autoload.php';
use Webdcg\Redis\Redis;
\$redis = new Redis();
\$redis->connect();
usleep(1000 * random_int(50, 100));
\$redis->rPush({$this->key}, 'A');
EOF
        );
        $this->producer->run();
    }

    /**
     * Queue Producer
     * Using the Symfony Process component, we connect to Redis and create
     * a single element on a Queue.
     * See: https://symfony.com/doc/current/index.html#gsc.tab=0.
     *
     * @return
     */
    protected function produceSingleHead()
    {
        $this->producer = new PhpProcess(
            <<<EOF
<?php
require __DIR__ . '/vendor/autoload.php';
use Webdcg\Redis\Redis;
\$redis = new Redis();
\$redis->connect();
usleep(1000 * random_int(50, 100));
\$redis->lPush({$this->key}, 'A');
EOF
        );
        $this->producer->run();
    }

    /** @test */
    public function redis_lists_brpoplpush()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->keyOptional));
        $this->produceSingleTail();
        // --------------------  T E S T  --------------------
        $this->assertEquals('A', $this->redis->bRPopLPush($this->key, $this->keyOptional, 1));
        $this->assertEquals('A', $this->redis->lPop($this->keyOptional));
        $this->assertEquals(0, $this->redis->exists($this->key));
        $this->assertEquals(0, $this->redis->exists($this->keyOptional));
    }

    /** @test */
    public function redis_lists_brpop()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $this->produceSingleTail();
        // --------------------  T E S T  --------------------
        $this->assertEquals([$this->key, 'A'], $this->redis->brPop([$this->key], 1));
        $this->assertEquals(0, $this->redis->exists($this->key));
    }

    /** @test */
    public function redis_lists_blpop()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $this->produceSingleHead();
        // --------------------  T E S T  --------------------
        $this->assertEquals([$this->key, 'A'], $this->redis->blPop([$this->key], 1));
        $this->assertEquals(0, $this->redis->exists($this->key));
    }

    /** @test */
    public function redis_lists_rpoplpush_float()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $this->assertEquals(1, $this->redis->rPush($this->key, 1.1));
        $this->assertEquals(2, $this->redis->rPush($this->key, 2.2));
        $this->assertEquals(3, $this->redis->rPush($this->key, 3.33));
        // --------------------  T E S T  --------------------
        $this->assertEquals(3.33, $this->redis->rPopLPush($this->key, $this->keyOptional));
        $this->assertEquals([1.1, 2.2], $this->redis->lRange($this->key));
        $this->assertEquals([3.33], $this->redis->lRange($this->keyOptional));
        // Cleanup used keys
        $this->assertEquals(1, $this->redis->delete($this->key));
        $this->assertEquals(1, $this->redis->delete($this->keyOptional));
    }

    /** @test */
    public function redis_lists_rpoplpush_int()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $this->assertEquals(1, $this->redis->rPush($this->key, 1));
        $this->assertEquals(2, $this->redis->rPush($this->key, 22));
        $this->assertEquals(3, $this->redis->rPush($this->key, 333));
        // --------------------  T E S T  --------------------
        $this->assertEquals(333, $this->redis->rPopLPush($this->key, $this->keyOptional));
        $this->assertEquals([1, 22], $this->redis->lRange($this->key));
        $this->assertEquals([333], $this->redis->lRange($this->keyOptional));
        // Cleanup used keys
        $this->assertEquals(1, $this->redis->delete($this->key));
        $this->assertEquals(1, $this->redis->delete($this->keyOptional));
    }

    /** @test */
    public function redis_lists_rpoplpush_string()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $this->assertEquals(1, $this->redis->rPush($this->key, 'A'));
        $this->assertEquals(2, $this->redis->rPush($this->key, 'B'));
        $this->assertEquals(3, $this->redis->rPush($this->key, 'C'));
        // --------------------  T E S T  --------------------
        $this->assertEquals('C', $this->redis->rPopLPush($this->key, $this->keyOptional));
        $this->assertEquals(['A', 'B'], $this->redis->lRange($this->key));
        $this->assertEquals(['C'], $this->redis->lRange($this->keyOptional));
        // Cleanup used keys
        $this->assertEquals(1, $this->redis->delete($this->key));
        $this->assertEquals(1, $this->redis->delete($this->keyOptional));
    }

    /** @test */
    public function redis_lists_rpop_float()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $this->assertEquals(1, $this->redis->rPush($this->key, 1.1));
        $this->assertEquals(2, $this->redis->rPush($this->key, 2.2));
        $this->assertEquals(3, $this->redis->rPush($this->key, 3.3));
        // --------------------  T E S T  --------------------
        $this->assertEquals(3.3, $this->redis->rPop($this->key));
        $this->assertEquals(2.2, $this->redis->rPop($this->key));
        $this->assertEquals(1.1, $this->redis->rPop($this->key));
        // Cleanup used keys
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_lists_rpop_int()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $this->assertEquals(1, $this->redis->rPush($this->key, 1));
        $this->assertEquals(2, $this->redis->rPush($this->key, 12));
        $this->assertEquals(3, $this->redis->rPush($this->key, 123));
        // --------------------  T E S T  --------------------
        $this->assertEquals(123, $this->redis->rPop($this->key));
        $this->assertEquals(12, $this->redis->rPop($this->key));
        $this->assertEquals(1, $this->redis->rPop($this->key));
        // Cleanup used keys
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_lists_rpop_string()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $this->assertEquals(1, $this->redis->rPush($this->key, 'A'));
        $this->assertEquals(2, $this->redis->rPush($this->key, 'B'));
        $this->assertEquals(3, $this->redis->rPush($this->key, 'C'));
        // --------------------  T E S T  --------------------
        $this->assertEquals('C', $this->redis->rPop($this->key));
        $this->assertEquals('B', $this->redis->rPop($this->key));
        $this->assertEquals('A', $this->redis->rPop($this->key));
        // Cleanup used keys
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_lists_lset()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $this->assertEquals(1, $this->redis->rPush($this->key, 'A'));
        $this->assertEquals(2, $this->redis->rPush($this->key, 'B'));
        $this->assertEquals(3, $this->redis->rPush($this->key, 'C'));
        $this->assertEquals('A', $this->redis->lIndex($this->key, 0));
        // --------------------  T E S T  --------------------
        $this->assertTrue($this->redis->lSet($this->key, 0, 'X'));
        $this->assertEquals('X', $this->redis->lIndex($this->key, 0));
        $this->assertTrue($this->redis->lSet($this->key, -1, 'Z'));
        $this->assertEquals('Z', $this->redis->lIndex($this->key, -1));
        // Cleanup used keys
        $this->assertEquals(1, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_lists_lpop_float()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $this->assertEquals(1, $this->redis->rPush($this->key, 1.1));
        $this->assertEquals(2, $this->redis->rPush($this->key, 2.2));
        $this->assertEquals(3, $this->redis->rPush($this->key, 3.3));
        // --------------------  T E S T  --------------------
        $this->assertEquals(1.1, $this->redis->lPop($this->key));
        $this->assertEquals(2.2, $this->redis->lPop($this->key));
        $this->assertEquals(3.3, $this->redis->lPop($this->key));
        // Cleanup used keys
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_lists_lpop_int()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $this->assertEquals(1, $this->redis->rPush($this->key, 1));
        $this->assertEquals(2, $this->redis->rPush($this->key, 12));
        $this->assertEquals(3, $this->redis->rPush($this->key, 123));
        // --------------------  T E S T  --------------------
        $this->assertEquals(1, $this->redis->lPop($this->key));
        $this->assertEquals(12, $this->redis->lPop($this->key));
        $this->assertEquals(123, $this->redis->lPop($this->key));
        // Cleanup used keys
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_lists_lpop_string()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $this->assertEquals(1, $this->redis->rPush($this->key, 'A'));
        $this->assertEquals(2, $this->redis->rPush($this->key, 'B'));
        $this->assertEquals(3, $this->redis->rPush($this->key, 'C'));
        // --------------------  T E S T  --------------------
        $this->assertEquals('A', $this->redis->lPop($this->key));
        $this->assertEquals('B', $this->redis->lPop($this->key));
        $this->assertEquals('C', $this->redis->lPop($this->key));
        // Cleanup used keys
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_lists_linsert_exception()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $this->assertEquals(1, $this->redis->rPush($this->key, 'A'));
        // --------------------  T E S T  --------------------
        $this->expectException(Exception::class);
        $this->assertEquals(2, $this->redis->lInsert($this->key, 'c', 'A', 'X'));
        // --------------------  T E S T  --------------------
        $this->assertEquals(['X', 'A'], $this->redis->lRange($this->key));
        // Cleanup used keys
        $this->assertEquals(1, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_lists_linsert_before()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $this->assertEquals(1, $this->redis->rPush($this->key, 'A'));
        // --------------------  T E S T  --------------------
        $this->assertEquals(2, $this->redis->lInsert($this->key, 'b', 'A', 'X'));
        $this->assertEquals(-1, $this->redis->lInsert($this->key, 'b', 'B', 'Y'));
        // --------------------  T E S T  --------------------
        $this->assertEquals(['X', 'A'], $this->redis->lRange($this->key));
        // Cleanup used keys
        $this->assertEquals(1, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_lists_linsert_after()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $this->assertEquals(1, $this->redis->rPush($this->key, 'A'));
        // --------------------  T E S T  --------------------
        $this->assertEquals(2, $this->redis->lInsert($this->key, 'a', 'A', 'X'));
        $this->assertEquals(-1, $this->redis->lInsert($this->key, 'a', 'B', 'Y'));
        // --------------------  T E S T  --------------------
        $this->assertEquals(['A', 'X'], $this->redis->lRange($this->key));
        // Cleanup used keys
        $this->assertEquals(1, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_lists_linsert_empty()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        // --------------------  T E S T  --------------------
        $this->assertEquals(0, $this->redis->lInsert($this->key, 'a', 'A', 'X'));
        $this->assertEquals(0, $this->redis->lInsert($this->key, 'b', 'A', 'X'));
    }

    /** @test */
    public function redis_lists_lGet_float()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        for ($i = 1; $i <= 10; $i++) {
            // --------------------  T E S T  --------------------
            $this->assertEquals($i, $this->redis->rPush($this->key, $i * 1.1));
        }
        // --------------------  T E S T  --------------------
        $this->assertEquals(1.1, $this->redis->lGet($this->key, 0));
        $this->assertEquals(11, $this->redis->lGet($this->key, 9));
        $this->assertEquals(11, $this->redis->lGet($this->key, -1));
        $this->assertEquals('', $this->redis->lGet($this->key, 10));
        // Cleanup used keys
        $this->assertEquals(1, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_lists_lGet_int()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        for ($i = 1; $i <= 10; $i++) {
            // --------------------  T E S T  --------------------
            $this->assertEquals($i, $this->redis->rPush($this->key, $i));
        }
        // --------------------  T E S T  --------------------
        $this->assertEquals(1, $this->redis->lGet($this->key, 0));
        $this->assertEquals(10, $this->redis->lGet($this->key, 9));
        $this->assertEquals(10, $this->redis->lGet($this->key, -1));
        $this->assertEquals('', $this->redis->lGet($this->key, 10));
        // Cleanup used keys
        $this->assertEquals(1, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_lists_lGet_string()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        for ($i = 1; $i <= 10; $i++) {
            // --------------------  T E S T  --------------------
            $this->assertEquals($i, $this->redis->rPush($this->key, "Item{$i}"));
        }
        // --------------------  T E S T  --------------------
        $this->assertEquals("Item1", $this->redis->lGet($this->key, 0));
        $this->assertEquals("Item10", $this->redis->lGet($this->key, 9));
        $this->assertEquals("Item10", $this->redis->lGet($this->key, -1));
        $this->assertEquals('', $this->redis->lGet($this->key, 10));
        // Cleanup used keys
        $this->assertEquals(1, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_lists_lindex_float()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        for ($i = 1; $i <= 10; $i++) {
            // --------------------  T E S T  --------------------
            $this->assertEquals($i, $this->redis->rPush($this->key, $i * 1.1));
        }
        // --------------------  T E S T  --------------------
        $this->assertEquals(1.1, $this->redis->lIndex($this->key, 0));
        $this->assertEquals(11, $this->redis->lIndex($this->key, 9));
        $this->assertEquals(11, $this->redis->lIndex($this->key, -1));
        $this->assertEquals('', $this->redis->lIndex($this->key, 10));
        // Cleanup used keys
        $this->assertEquals(1, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_lists_lindex_int()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        for ($i = 1; $i <= 10; $i++) {
            // --------------------  T E S T  --------------------
            $this->assertEquals($i, $this->redis->rPush($this->key, $i));
        }
        // --------------------  T E S T  --------------------
        $this->assertEquals(1, $this->redis->lIndex($this->key, 0));
        $this->assertEquals(10, $this->redis->lIndex($this->key, 9));
        $this->assertEquals(10, $this->redis->lIndex($this->key, -1));
        $this->assertEquals('', $this->redis->lIndex($this->key, 10));
        // Cleanup used keys
        $this->assertEquals(1, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_lists_lindex_string()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        for ($i = 1; $i <= 10; $i++) {
            // --------------------  T E S T  --------------------
            $this->assertEquals($i, $this->redis->rPush($this->key, "Item{$i}"));
        }
        // --------------------  T E S T  --------------------
        $this->assertEquals("Item1", $this->redis->lIndex($this->key, 0));
        $this->assertEquals("Item10", $this->redis->lIndex($this->key, 9));
        $this->assertEquals("Item10", $this->redis->lIndex($this->key, -1));
        $this->assertEquals('', $this->redis->lIndex($this->key, 10));
        // Cleanup used keys
        $this->assertEquals(1, $this->redis->delete($this->key));
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
