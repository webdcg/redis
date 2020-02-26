<?php

namespace Webdcg\Redis\Tests;

use PHPUnit\Framework\TestCase;
use Webdcg\Redis\Redis;

class RedisTransactionsTest extends TestCase
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
        $this->key = 'Transactions';
        $this->keyOptional = 'Transactions:Optional';
    }

    /*
     * ========================================================================
     * Redis | Transactions | unwatch => Flushes all the previously watched keys for a transaction.
     * ========================================================================
     */


    /** @test */
    public function redis_transactions_multi_unwatch_sucseed()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $this->assertTrue($this->redis->set($this->key, 1));
        $this->assertEquals(1, $this->redis->get($this->key));
        // Start watching a key
        $this->assertTrue($this->redis->watch($this->key));
        // Use the key before its released by a transaction
        $this->assertEquals(2, $this->redis->incr($this->key));
        // Since we made a chance let's unwatch the key
        $this->assertTrue($this->redis->unwatch($this->key));
        // If we try to use the same key it the transaction should fail
        $multi = $this->redis->multi();
        $multi->incr($this->key);
        $multi->get($this->key);
        $transaction = $multi->exec();
        $this->assertEquals([3, 3], $transaction);
        $this->assertEquals(3, $this->redis->get($this->key));
        $this->assertEquals(1, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_transactions_multi_watch_sucseed()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $this->assertTrue($this->redis->set($this->key, 1));
        $this->assertEquals(1, $this->redis->get($this->key));
        // Start watching a key
        $this->assertTrue($this->redis->watch($this->key));
        // If we try to use the same key it the transaction should fail
        $multi = $this->redis->multi();
        $multi->incr($this->key);
        $multi->get($this->key);
        $transaction = $multi->exec();
        $this->assertEquals([2, 2], $transaction);
        $this->assertEquals(2, $this->redis->get($this->key));
        // Use the key after its released by a transaction
        $this->assertEquals(3, $this->redis->incr($this->key));
        $this->assertEquals(1, $this->redis->delete($this->key));
    }


    /** @test */
    public function redis_transactions_multi_watch_discards()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $this->assertTrue($this->redis->set($this->key, 1));
        $this->assertEquals(1, $this->redis->get($this->key));
        // Start watching a key
        $this->assertTrue($this->redis->watch($this->key));
        // Use the key before its released by a transaction
        $this->assertEquals(2, $this->redis->incr($this->key));
        // If we try to use the same key it the transaction should fail
        $multi = $this->redis->multi();
        $multi->incr($this->key);
        $multi->get($this->key);
        $transaction = $multi->exec();
        $this->assertFalse($transaction);
        $this->assertEquals(2, $this->redis->get($this->key));
        $this->assertEquals(1, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_transactions_multi_discard()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $multi = $this->redis->multi();
        $multi->set($this->key, 1);
        $multi->get($this->key);
        $transaction = $this->redis->discard($multi);
        $this->assertEquals(true, $transaction);
        $this->assertFalse($this->redis->get($this->key));
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_transactions_multi_local_exec()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $multi = $this->redis->multi();
        $multi->set($this->key, 1);
        $multi->get($this->key);
        $transaction = $this->redis->exec($multi);
        $this->assertEquals([true, 1], $transaction);
        $this->assertEquals(1, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_transactions_multi_step_exec()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $multi = $this->redis->multi();
        $multi->set($this->key, 1);
        $multi->get($this->key);
        $transaction = $multi->exec();
        $this->assertEquals([true, 1], $transaction);
        $this->assertEquals(1, $this->redis->get($this->key));
        $this->assertEquals(1, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_transactions_multi_exec()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $transaction = $this->redis
                        ->multi()
                        ->set($this->key, 1)
                        ->get($this->key)
                        ->exec();
        $this->assertEquals([true, 1], $transaction);
        $this->assertEquals(1, $this->redis->get($this->key));
        $this->assertEquals(1, $this->redis->delete($this->key));
    }
}
