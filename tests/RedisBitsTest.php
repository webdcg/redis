<?php

namespace Webdcg\Redis\Tests;

use PHPUnit\Framework\TestCase;
use Webdcg\Redis\Exceptions\BitwiseOperationException;
use Webdcg\Redis\Redis;

class RedisBitsTest extends TestCase
{
    protected $redis;
    protected $key;
    protected $keyOptional;

    protected function setUp(): void
    {
        $this->redis = new Redis;
        $this->redis->connect();
        $this->key = 'Bits';
        $this->keyOptional = 'BitsOptional';
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
        $this->assertTrue($this->redis->set($this->key, 0));
        $this->assertTrue($this->redis->set($this->keyOptional, 1));
        $this->assertEquals(2, $this->redis->bitCount($this->key));
        $this->assertEquals(3, $this->redis->bitCount($this->keyOptional));

        // Perform an AND bitwise operation between the two
        $this->assertEquals(1, $this->redis->bitOp('and', 'testBitOpAnd', $this->key, $this->keyOptional));

        // Check that the original values remain
        $this->assertEquals(0, $this->redis->get($this->key));
        $this->assertEquals(1, $this->redis->get($this->keyOptional));

        // Verify the opput of the operation
        $this->assertEquals(0, $this->redis->get('testBitOpAnd'));
        $this->assertEquals(2, $this->redis->bitCount('testBitOpAnd'));

        // Remove all the keys used
        $this->assertEquals(3, $this->redis->delete([$this->key, $this->keyOptional, 'testBitOpAnd']));
    }

    /** @test */
    public function redis_bits_bitop_or_operation()
    {
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $this->assertGreaterThanOrEqual(0, $this->redis->setBit($this->key, 0, 0));
        $this->assertGreaterThanOrEqual(0, $this->redis->setBit($this->key, 1, 1));
        $this->assertGreaterThanOrEqual(0, $this->redis->setBit($this->key, 2, 0));
        $this->assertGreaterThanOrEqual(0, $this->redis->setBit($this->key, 3, 1));
        $this->assertGreaterThanOrEqual(0, $this->redis->setBit($this->key, 4, 0));
        $this->assertGreaterThanOrEqual(0, $this->redis->setBit($this->key, 5, 1));
        $this->assertGreaterThanOrEqual(0, $this->redis->setBit($this->key, 6, 0));
        $this->assertGreaterThanOrEqual(0, $this->redis->setBit($this->key, 7, 1));
        $this->assertEquals(4, $this->redis->bitCount($this->key));
        $value = $this->redis->get($this->key);
        $this->assertEquals('1010101', $this->getBinaryString($value));
        $this->assertEquals(85, ord($value));

        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->keyOptional));
        $this->assertGreaterThanOrEqual(0, $this->redis->setBit($this->keyOptional, 0, 1));
        $this->assertGreaterThanOrEqual(0, $this->redis->setBit($this->keyOptional, 1, 0));
        $this->assertGreaterThanOrEqual(0, $this->redis->setBit($this->keyOptional, 2, 1));
        $this->assertGreaterThanOrEqual(0, $this->redis->setBit($this->keyOptional, 3, 0));
        $this->assertGreaterThanOrEqual(0, $this->redis->setBit($this->keyOptional, 4, 1));
        $this->assertGreaterThanOrEqual(0, $this->redis->setBit($this->keyOptional, 5, 0));
        $this->assertGreaterThanOrEqual(0, $this->redis->setBit($this->keyOptional, 6, 1));
        $this->assertGreaterThanOrEqual(0, $this->redis->setBit($this->keyOptional, 7, 0));
        $this->assertEquals(4, $this->redis->bitCount($this->keyOptional));
        $value = $this->redis->get($this->keyOptional);
        $this->assertEquals('10101010', $this->getBinaryString($value));
        $this->assertEquals(170, ord($value));

        $this->assertEquals(1, $this->redis->bitOp('or', 'testBitOpOr', $this->key, $this->keyOptional));
        $value = $this->redis->get('testBitOpOr');
        $this->assertEquals('11111111', $this->getBinaryString($value));

        // Remove all the keys used
        $this->assertEquals(3, $this->redis->delete([$this->key, $this->keyOptional, 'testBitOpOr']));
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
