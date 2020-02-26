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
     * Redis | Transactions | discard => Flushes all previously queued commands in a transaction and restores the connection state to normal.
     * ========================================================================
     */
<<<<<<< HEAD

=======
    

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
    
>>>>>>> Redis | Transactions | discard => Flushes all previously queued commands in a transaction and restores the connection state to normal.
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
