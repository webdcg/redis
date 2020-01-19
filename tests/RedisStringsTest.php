<?php

namespace Webdcg\Redis\Tests;

use PHPUnit\Framework\TestCase;
use Webdcg\Redis\Redis;

class RedisStringsTest extends TestCase
{
    protected $redis;
    protected $key;

    protected function setUp(): void
    {
        $this->redis = new Redis;
        $this->redis->connect();
        $this->key = 'Strings';
    }

    /** @test */
    public function redis_strings_append()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $this->assertTrue($this->redis->set($this->key, 'value1'));
        $this->assertEquals(12, $this->redis->append($this->key, 'value2'));
        $this->assertEquals('value1value2', $this->redis->get($this->key));
        // Cleanup used keys
        $this->assertEquals(1, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_strings_append_emptry()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $this->assertTrue($this->redis->set($this->key, 'value1'));
        $this->assertEquals(6, $this->redis->append($this->key, ''));
        $this->assertEquals('value1', $this->redis->get($this->key));
        // Cleanup used keys
        $this->assertEquals(1, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_strings_append_numbers()
    {
        $number = random_int(100, 999);
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $this->assertTrue($this->redis->set($this->key, 'value1'));
        $this->assertEquals(9, $this->redis->append($this->key, $number));
        $this->assertEquals("value1{$number}", $this->redis->get($this->key));
        // Cleanup used keys
        $this->assertEquals(1, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_strings_decr()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $this->assertTrue($this->redis->set($this->key, 1));
        $this->assertEquals(0, $this->redis->decr($this->key));
        // Cleanup used keys
        $this->assertEquals(1, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_strings_decr_negative()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $this->assertTrue($this->redis->set($this->key, -1));
        $this->assertEquals(-2, $this->redis->decr($this->key));
        // Cleanup used keys
        $this->assertEquals(1, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_strings_decr_not_a_number()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $this->assertTrue($this->redis->set($this->key, 'A'));
        $this->assertEquals(0, $this->redis->decr($this->key));
        $this->assertEquals('A', $this->redis->get($this->key));
        // Cleanup used keys
        $this->assertEquals(1, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_strings_decrby()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $this->assertTrue($this->redis->set($this->key, 5));
        $this->assertEquals(2, $this->redis->decrBy($this->key, 3));
        // Cleanup used keys
        $this->assertEquals(1, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_strings_decrby_negative()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $this->assertTrue($this->redis->set($this->key, -1));
        $this->assertEquals(-3, $this->redis->decrBy($this->key, 2));
        // Cleanup used keys
        $this->assertEquals(1, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_strings_decrby_not_a_number()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $this->assertTrue($this->redis->set($this->key, 'A'));
        $this->assertEquals(0, $this->redis->decrBy($this->key, 5));
        $this->assertEquals('A', $this->redis->get($this->key));
        // Cleanup used keys
        $this->assertEquals(1, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_strings_incr()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $this->assertTrue($this->redis->set($this->key, 1));
        $this->assertEquals(2, $this->redis->incr($this->key));
        // Cleanup used keys
        $this->assertEquals(1, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_strings_incr_negative()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $this->assertTrue($this->redis->set($this->key, -1));
        $this->assertEquals(0, $this->redis->incr($this->key));
        // Cleanup used keys
        $this->assertEquals(1, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_strings_incr_not_a_number()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $this->assertTrue($this->redis->set($this->key, 'A'));
        $this->assertEquals(0, $this->redis->incr($this->key));
        $this->assertEquals('A', $this->redis->get($this->key));
        // Cleanup used keys
        $this->assertEquals(1, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_strings_incrby()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $this->assertTrue($this->redis->set($this->key, 2));
        $this->assertEquals(5, $this->redis->incrBy($this->key, 3));
        // Cleanup used keys
        $this->assertEquals(1, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_strings_incrby_negative()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $this->assertTrue($this->redis->set($this->key, -1));
        $this->assertEquals(1, $this->redis->incrBy($this->key, 2));
        // Cleanup used keys
        $this->assertEquals(1, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_strings_incrby_not_a_number()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $this->assertTrue($this->redis->set($this->key, 'A'));
        $this->assertEquals(0, $this->redis->incrBy($this->key, 5));
        $this->assertEquals('A', $this->redis->get($this->key));
        // Cleanup used keys
        $this->assertEquals(1, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_strings_incrbyfloat()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $this->assertTrue($this->redis->set($this->key, 1.1));
        $this->assertEquals(3.3, $this->redis->incrByFloat($this->key, 2.2));
        // Cleanup used keys
        $this->assertEquals(1, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_strings_incrbyfloat_negative()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $this->assertTrue($this->redis->set($this->key, -1.1));
        $this->assertEquals(1.1, $this->redis->incrByFloat($this->key, 2.2));
        // Cleanup used keys
        $this->assertEquals(1, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_strings_incrbyfloat_not_a_number()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $this->assertTrue($this->redis->set($this->key, 'A'));
        $this->assertEquals(0, $this->redis->incrByFloat($this->key, 1.5));
        $this->assertEquals('A', $this->redis->get($this->key));
        // Cleanup used keys
        $this->assertEquals(1, $this->redis->delete($this->key));
    }

    public function redis_strings_set()
    {
        // Simple key -> value set
        $this->assertTrue($this->redis->set($this->key, 'value'));
        // Will redirect, and actually make an SETEX call
        $this->assertTrue($this->redis->set($this->key, 'value', 10));
        // Will set the key, if it doesn't exist, with a ttl of 10 seconds
        $this->assertTrue($this->redis->set('key:'.time(), 'value', ['nx', 'ex' => 10]));
        $this->assertFalse($this->redis->set('key:'.time(), 'value', ['nx', 'ex' => 10]));
        // Will set a key, if it does exist, with a ttl of 1000 miliseconds
        $this->assertTrue($this->redis->set($this->key, 'value', ['xx', 'px' => 1000]));
    }

    public function redis_strings_setex()
    {
        $this->assertTrue($this->redis->setEx('key', 10, 'value'));
    }
}
