<?php

namespace Webdcg\Redis\Tests;

use Exception;
use PHPUnit\Framework\TestCase;
use Webdcg\Redis\Exceptions\BitwiseOperationException;
use Webdcg\Redis\Redis;

class RedisBitsTest extends TestCase
{
    protected $redis;

    protected function setUp(): void
    {
        $this->redis = new Redis;
        $this->redis->connect();
    }

    /**
     * Generate a string with 0s and 1s of the binary representaion of a value.
     *
     * @param  $value
     *
     * @return string   String representation of value with 0s and 1s
     */
    protected function getBinaryString($value): string
    {
        return base_convert(unpack('H*', $value)[1], 16, 2);
    }

    /** @test */
    public function redis_bits_bitcount()
    {
        $this->assertTrue($this->redis->set('key', 'a'));
        $value = $this->redis->get('key');
        $this->assertEquals(97, ord($value));
        $this->assertEquals('1100001', $this->getBinaryString($value));
        $this->assertEquals(3, $this->redis->bitCount('key'));
        $this->assertEquals(1, $this->redis->delete('key'));
        $this->assertEquals(0, $this->redis->exists('key'));
    }

    /** @test */
    public function redis_bits_bitop_unrecognized_operation()
    {
        $this->assertTrue($this->redis->set('testBit', 'A'));
        $this->expectException(BitwiseOperationException::class);
        $this->assertEquals(1, $this->redis->bitOp('nor', 'testBitOp', 'testBit'));
        $this->assertEquals(1, $this->redis->delete('testBit'));
    }

    /** @test */
    public function redis_bits_bitop_not_operation()
    {
        $this->assertGreaterThanOrEqual(0, $this->redis->delete('testBit'));
        // ASCII 65 A
        // 0 1 0 0 0 0 0 1
        // 0 1 2 3 4 5 6 7
        $this->assertTrue($this->redis->set('testBit', 'A'));
        $value = $this->redis->get('testBit');
        $this->assertEquals('1000001', $this->getBinaryString($value));
        $this->assertEquals(65, ord($value));

        $this->assertEquals(1, $this->redis->bitOp('not', 'testBitOpNot', 'testBit'));

        $value = $this->redis->get('testBitOpNot');
        $this->assertEquals('10111110', $this->getBinaryString($value));
        $this->assertEquals(190, ord($value));

        // Remove all the keys used
        $this->assertEquals(2, $this->redis->delete(['testBit', 'testBitOpNot']));
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

    /** @test */
    public function redis_bits_setbit()
    {
        // A ASCII 65 01000001
        $this->assertTrue($this->redis->set('testBit', 'A'));
        // Modify the second bit, it was 0 previously
        $this->assertEquals(0, $this->redis->setBit('testBit', 2, 1));
        // a ASCII 97 01100001
        $this->assertEquals('a', $this->redis->get('testBit'));
        // Remove all the keys used
        $this->assertEquals(1, $this->redis->delete('testBit'));
    }

    /** @test */
    public function redis_bits_getbit()
    {
        // A ASCII 65 01000001
        $this->assertTrue($this->redis->set('testBit', 'A'));
        $this->assertEquals('A', $this->redis->get('testBit'));
        $this->assertEquals(0, $this->redis->getBit('testBit', 0));
        $this->assertEquals(1, $this->redis->getBit('testBit', 1));
        $this->assertEquals(0, $this->redis->getBit('testBit', 2));
        $this->assertEquals(0, $this->redis->getBit('testBit', 3));
        $this->assertEquals(0, $this->redis->getBit('testBit', 4));
        $this->assertEquals(0, $this->redis->getBit('testBit', 5));
        $this->assertEquals(0, $this->redis->getBit('testBit', 6));
        $this->assertEquals(1, $this->redis->getBit('testBit', 7));
        // Remove all the keys used
        $this->assertEquals(1, $this->redis->delete('testBit'));
    }
}
