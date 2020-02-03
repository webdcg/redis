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

    /** @test */
    public function redis_transactions_multi_exec()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $this->assertEquals([true, 1], $this->redis->multi()->set($this->key, 1)->get($this->key)->exec());
        $this->assertEquals(1, $this->redis->delete($this->key));
    }
}
