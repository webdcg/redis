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
    public function redis_lists_lpush()
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
