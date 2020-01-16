<?php

namespace Webdcg\Redis\Tests;

use Exception;
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
        $this->assertEquals(1, $this->redis->delete('key'));
        $this->assertEquals(0, $this->redis->exists('key'));
    }

    /** @test */
    public function redis_bits_bitop_unrecognized_operation()
    {
        $this->assertTrue($this->redis->set('testBit', 'A'));
        $this->expectException(Exception::class);
        $this->assertEquals(1, $this->redis->bitOp('nor', 'testBitOp', 'testBit'));
    }
}
