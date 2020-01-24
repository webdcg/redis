<?php

namespace Webdcg\Redis\Tests;

use PHPUnit\Framework\TestCase;
use Webdcg\Redis\Exceptions\BitwiseOperationException;
use Webdcg\Redis\Redis;

class RedisBitsTest extends TestCase
{
    protected $redis;
    protected $key;

    protected function setUp(): void
    {
        $this->redis = new Redis;
        $this->redis->connect();
        $this->key = 'Bits';
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
        $this->assertTrue($this->redis->set($this->key, 'a'));
        $value = $this->redis->get($this->key);
        $this->assertEquals(97, ord($value));
        $this->assertEquals('1100001', $this->getBinaryString($value));
        $this->assertEquals(3, $this->redis->bitCount($this->key));
        $this->assertEquals(1, $this->redis->delete($this->key));
        $this->assertEquals(0, $this->redis->exists($this->key));
    }

    /** @test */
    public function redis_bits_bitop_unrecognized_operation()
    {
        $this->assertTrue($this->redis->set($this->key, 'A'));
        $this->expectException(BitwiseOperationException::class);
        $this->assertEquals(1, $this->redis->bitOp('nor', 'testBitOp', $this->key));
        $this->assertEquals(1, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_bits_bitop_not_operation()
    {
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        // ASCII 65 A
        // 0 1 0 0 0 0 0 1
        // 0 1 2 3 4 5 6 7
        $this->assertTrue($this->redis->set($this->key, 'A'));
        $value = $this->redis->get($this->key);
        $this->assertEquals('1000001', $this->getBinaryString($value));
        $this->assertEquals(65, ord($value));

        $this->assertEquals(1, $this->redis->bitOp('not', 'testBitOpNot', $this->key));

        $value = $this->redis->get('testBitOpNot');
        $this->assertEquals('10111110', $this->getBinaryString($value));
        $this->assertEquals(190, ord($value));

        // Remove all the keys used
        $this->assertEquals(2, $this->redis->delete([$this->key, 'testBitOpNot']));
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
    public function redis_bits_bitop_or_operation()
    {
        $this->assertGreaterThanOrEqual(0, $this->redis->delete('testBit1'));
        $this->assertGreaterThanOrEqual(0, $this->redis->setBit('testBit1', 0, 0));
        $this->assertGreaterThanOrEqual(0, $this->redis->setBit('testBit1', 1, 1));
        $this->assertGreaterThanOrEqual(0, $this->redis->setBit('testBit1', 2, 0));
        $this->assertGreaterThanOrEqual(0, $this->redis->setBit('testBit1', 3, 1));
        $this->assertGreaterThanOrEqual(0, $this->redis->setBit('testBit1', 4, 0));
        $this->assertGreaterThanOrEqual(0, $this->redis->setBit('testBit1', 5, 1));
        $this->assertGreaterThanOrEqual(0, $this->redis->setBit('testBit1', 6, 0));
        $this->assertGreaterThanOrEqual(0, $this->redis->setBit('testBit1', 7, 1));
        $this->assertEquals(4, $this->redis->bitCount('testBit1'));
        $value = $this->redis->get('testBit1');
        $this->assertEquals('1010101', $this->getBinaryString($value));
        $this->assertEquals(85, ord($value));

        $this->assertGreaterThanOrEqual(0, $this->redis->delete('testBit2'));
        $this->assertGreaterThanOrEqual(0, $this->redis->setBit('testBit2', 0, 1));
        $this->assertGreaterThanOrEqual(0, $this->redis->setBit('testBit2', 1, 0));
        $this->assertGreaterThanOrEqual(0, $this->redis->setBit('testBit2', 2, 1));
        $this->assertGreaterThanOrEqual(0, $this->redis->setBit('testBit2', 3, 0));
        $this->assertGreaterThanOrEqual(0, $this->redis->setBit('testBit2', 4, 1));
        $this->assertGreaterThanOrEqual(0, $this->redis->setBit('testBit2', 5, 0));
        $this->assertGreaterThanOrEqual(0, $this->redis->setBit('testBit2', 6, 1));
        $this->assertGreaterThanOrEqual(0, $this->redis->setBit('testBit2', 7, 0));
        $this->assertEquals(4, $this->redis->bitCount('testBit2'));
        $value = $this->redis->get('testBit2');
        $this->assertEquals('10101010', $this->getBinaryString($value));
        $this->assertEquals(170, ord($value));

        $this->assertEquals(1, $this->redis->bitOp('or', 'testBitOpOr', 'testBit1', 'testBit2'));
        $value = $this->redis->get('testBitOpOr');
        $this->assertEquals('11111111', $this->getBinaryString($value));

        // Remove all the keys used
        $this->assertEquals(3, $this->redis->delete(['testBit1', 'testBit2', 'testBitOpOr']));
    }

    /** @test */
    public function redis_bits_setbit()
    {
        // A ASCII 65 01000001
        $this->assertTrue($this->redis->set($this->key, 'A'));
        // Modify the second bit, it was 0 previously
        $this->assertEquals(0, $this->redis->setBit($this->key, 2, 1));
        // a ASCII 97 01100001
        $this->assertEquals('a', $this->redis->get($this->key));
        // Remove all the keys used
        $this->assertEquals(1, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_bits_getbit()
    {
        // A ASCII 65 01000001
        $this->assertTrue($this->redis->set($this->key, 'A'));
        $this->assertEquals('A', $this->redis->get($this->key));
        $this->assertEquals(0, $this->redis->getBit($this->key, 0));
        $this->assertEquals(1, $this->redis->getBit($this->key, 1));
        $this->assertEquals(0, $this->redis->getBit($this->key, 2));
        $this->assertEquals(0, $this->redis->getBit($this->key, 3));
        $this->assertEquals(0, $this->redis->getBit($this->key, 4));
        $this->assertEquals(0, $this->redis->getBit($this->key, 5));
        $this->assertEquals(0, $this->redis->getBit($this->key, 6));
        $this->assertEquals(1, $this->redis->getBit($this->key, 7));
        // Remove all the keys used
        $this->assertEquals(1, $this->redis->delete($this->key));
    }
}
