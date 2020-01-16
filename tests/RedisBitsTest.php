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
        $this->assertEquals(1, $this->redis->delete('testBit'));
    }

    /** @test */
    public function redis_bits_bitop_and_operation()
    {
        // Set a couple strings
        $this->assertTrue($this->redis->set('testBit1', 0));
        $this->assertTrue($this->redis->set('testBit2', 1));
        $this->assertEquals(2, $this->redis->bitCount('testBit1'));
        $this->assertEquals(3, $this->redis->bitCount('testBit2'));
        
        // Perform an AND bitwise operation between the two
        $this->assertEquals(1, $this->redis->bitOp('and', 'testBitOpAnd', 'testBit1', 'testBit2'));
        
        // Check that the original values remain
        $this->assertEquals(0, $this->redis->get('testBit1'));
        $this->assertEquals(1, $this->redis->get('testBit2'));

        // Verify the opput of the operation
        $this->assertEquals(0, $this->redis->get('testBitOpAnd'));
        $this->assertEquals(2, $this->redis->bitCount('testBitOpAnd'));

        // Remove all the keys used
        $this->assertEquals(3, $this->redis->delete(['testBit1', 'testBit2', 'testBitOpAnd']));
    }
}
