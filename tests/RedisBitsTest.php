<?php

namespace Webdcg\Redis\Tests;

use PHPUnit\Framework\TestCase;
use Webdcg\Redis\Redis;

class RedisBitsTest extends TestCase
{
    protected $redis;

    protected function setUp(): void
    {
        $this->redis = new Redis;
        $this->redis->connect();
    }

    /** @test */
    public function redis_bits_bitcount()
    {
        $this->assertTrue($this->redis->set('key', 'a'));
        $value = $this->redis->get('key');
        $this->assertEquals(97, ord($value));
        $this->assertEquals('1100001', base_convert(unpack('H*', $value)[1], 16, 2));
        $this->assertEquals(3, $this->redis->bitCount('key'));
    }
}
