<?php

namespace Webdcg\Redis\Tests;

use PHPUnit\Framework\TestCase;
use Webdcg\Redis\Redis;

class RedisHashesTest extends TestCase
{
    protected $redis;
    protected $key;

    protected function setUp(): void
    {
        $this->redis = new Redis();
        $this->redis->connect();
        $this->redis->setOption(\Redis::OPT_SERIALIZER, \Redis::SERIALIZER_NONE);
        $this->key = 'Hashes';
    }

    /** @test */
    public function redis_hashes_hdel()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $this->assertTrue($this->redis->set($this->key, 'value'));
        $this->assertEquals('value', $this->redis->get($this->key));
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
    }
}
