<?php

namespace Webdcg\Redis\Tests;

use PHPUnit\Framework\TestCase;
use Webdcg\Redis\Exceptions\NotAssociativeArrayException;
use Webdcg\Redis\Redis;

class RedisStringsTest extends TestCase
{
    protected $redis;
    protected $key;

    protected function setUp(): void
    {
        $this->redis = new Redis();
        $this->redis->connect();
        $this->key = 'Strings';
    }

    /** @test */
    public function redis_strings_set_string()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        // Simple key -> value set
        $this->assertTrue($this->redis->set($this->key, 'value'));
        $this->assertEquals('value', $this->redis->get($this->key));
        $this->assertIsString($this->redis->get($this->key));
        // Cleanup used keys
        $this->assertEquals(1, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_strings_set_ttl()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        // Will redirect, and actually make an SETEX call
        $this->assertTrue($this->redis->set($this->key, 'value', 1));
        $this->assertEquals('value', $this->redis->get($this->key));
        $this->assertIsString($this->redis->get($this->key));
        usleep(1.1 * 1000000);
        $this->assertFalse($this->redis->get($this->key));
    }

    /** @test */
    public function redis_strings_set_options_ex()
    {
        $this->key = 'key:'.time();
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        // Will set the key, if it doesn't exist, with a ttl of 1 seconds
        $this->assertTrue($this->redis->set($this->key, 'value', ['nx', 'ex' => 1]));
        $this->assertFalse($this->redis->set($this->key, 'value', ['nx', 'ex' => 1]));
        $this->assertEquals('value', $this->redis->get($this->key));
        $this->assertIsString($this->redis->get($this->key));
        usleep(1.1 * 1000000);
        $this->assertFalse($this->redis->get($this->key));
    }

    /** @test */
    public function redis_strings_set_options_px()
    {
        $this->key = 'key:'.time();
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $this->assertTrue($this->redis->set($this->key, 'value'));
        // Will set a key, if it does exist, with a ttl of 10 miliseconds
        $this->assertTrue($this->redis->set($this->key, 'value', ['xx', 'px' => 10]));
        $this->assertEquals('value', $this->redis->get($this->key));
        $this->assertIsString($this->redis->get($this->key));
        usleep(20 * 1000);
        $this->assertFalse($this->redis->get($this->key));
    }

    /** @test */
    public function redis_strings_setnx_nonexisting()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $this->assertTrue($this->redis->setNx($this->key, 'value'));
        $this->assertEquals('value', $this->redis->get($this->key));
        // Cleanup used keys
        $this->assertEquals(1, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_strings_setnx_existing()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $this->assertTrue($this->redis->set($this->key, 'value'));
        $this->assertFalse($this->redis->setNx($this->key, 'value'));
        $this->assertEquals('value', $this->redis->get($this->key));
        // Cleanup used keys
        $this->assertEquals(1, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_strings_setex()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $this->assertTrue($this->redis->setEx($this->key, 1, 'value'));
        $this->assertEquals('value', $this->redis->get($this->key));
        usleep(1.1 * 1000000);
        $this->assertFalse($this->redis->get($this->key));
    }

    /** @test */
    public function redis_strings_psetex()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $this->assertTrue($this->redis->pSetEx($this->key, 10, 'value'));
        $this->assertEquals('value', $this->redis->get($this->key));
        usleep(20 * 1000);
        $this->assertFalse($this->redis->get($this->key));
    }

    /** @test */
    public function redis_strings_get_nonexisting()
    {
        $this->assertEquals(false, $this->redis->get('nonexisting'));
        $this->assertFalse($this->redis->get('nonexisting'));
    }

    /** @test */
    public function redis_strings_get_string()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $this->assertTrue($this->redis->set($this->key, 'value'));
        $this->assertEquals('value', $this->redis->get($this->key));
        $this->assertIsString($this->redis->get($this->key));
        // Cleanup used keys
        $this->assertEquals(1, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_strings_get_int()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $this->assertTrue($this->redis->set($this->key, 123));
        $this->assertEquals(123, $this->redis->get($this->key));
        $this->assertIsString($this->redis->get($this->key));
        // Cleanup used keys
        $this->assertEquals(1, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_strings_get_float()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $this->assertTrue($this->redis->set($this->key, 3.141592));
        $this->assertEquals(3.141592, $this->redis->get($this->key));
        $this->assertIsString($this->redis->get($this->key));
        // Cleanup used keys
        $this->assertEquals(1, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_strings_get_boolean()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $this->assertTrue($this->redis->set($this->key, true));
        $this->assertEquals(true, $this->redis->get($this->key));
        $this->assertIsString($this->redis->get($this->key));
        // Cleanup used keys
        $this->assertEquals(1, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_strings_get_json()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $this->assertTrue($this->redis->set($this->key, json_encode(['tswift' => 'Taylor Swift'])));
        $this->assertEquals((object) ['tswift' => 'Taylor Swift'], json_decode($this->redis->get($this->key)));
        $this->assertIsObject(json_decode($this->redis->get($this->key)));
        // Cleanup used keys
        $this->assertEquals(1, $this->redis->delete($this->key));
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
    public function redis_strings_incrbyfloat_negative_increment()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $this->assertTrue($this->redis->set($this->key, -1.1));
        $this->assertEquals(-2.2, $this->redis->incrByFloat($this->key, -1.1));
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

    /** @test */
    public function redis_strings_strlen()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $this->assertTrue($this->redis->set($this->key, 'value1'));
        $this->assertEquals(6, $this->redis->strLen($this->key));
        $this->assertEquals('value1', $this->redis->get($this->key));
        // Cleanup used keys
        $this->assertEquals(1, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_strings_strlen_nonexisting()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $this->assertEquals(0, $this->redis->strLen('nonexisting'));
    }

    /** @test */
    public function redis_strings_getrange()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $this->assertTrue($this->redis->set($this->key, 'Taylor Swift'));
        $this->assertEquals('Taylor', $this->redis->getRange($this->key, 0, 5));
        $this->assertEquals('Swift', $this->redis->getRange($this->key, -5, -1));
        $this->assertEquals('Taylor Swift', $this->redis->get($this->key));
        // Cleanup used keys
        $this->assertEquals(1, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_strings_getrange_nonexisting()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $this->assertEquals('', $this->redis->getRange('nonexisting', 0, 5));
    }

    /** @test */
    public function redis_strings_setrange()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $this->assertTrue($this->redis->set($this->key, 'Hello World'));
        $this->assertEquals(11, $this->redis->setRange($this->key, 6, 'Redis'));
        $this->assertEquals('Hello Redis', $this->redis->get($this->key));
        // Cleanup used keys
        $this->assertEquals(1, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_strings_getset()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $this->assertTrue($this->redis->set($this->key, 'Taylor Swift'));
        $this->assertEquals('Taylor Swift', $this->redis->getSet($this->key, 'Milla Jovovich'));
        $this->assertEquals('Milla Jovovich', $this->redis->get($this->key));
        // Cleanup used keys
        $this->assertEquals(1, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_strings_mget()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete(['tswift', 'millaj', 'kbeck']));
        $this->assertTrue($this->redis->set('tswift', 'Taylor Swift'));
        $this->assertTrue($this->redis->set('millaj', 'Milla Jovovich'));
        $this->assertTrue($this->redis->set('kbeck', 'Kate Beckinsale'));
        $stars = $this->redis->mGet(['tswift', 'millaj', 'kbeck']);
        $this->assertContains('Taylor Swift', $stars);
        $this->assertContains('Milla Jovovich', $stars);
        $this->assertContains('Kate Beckinsale', $stars);
        $this->assertSame(['Taylor Swift', 'Milla Jovovich', 'Kate Beckinsale'], $stars);
        $this->assertEquals(['Taylor Swift', 'Milla Jovovich', 'Kate Beckinsale'], $stars);
        // Cleanup used keys
        $this->assertEquals(3, $this->redis->delete(['tswift', 'millaj', 'kbeck']));
    }

    /** @test */
    public function redis_strings_mget_nonexisting()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete(['tswift', 'millaj', 'kbeck']));
        $this->assertTrue($this->redis->set('tswift', 'Taylor Swift'));
        $this->assertTrue($this->redis->set('millaj', 'Milla Jovovich'));
        $this->assertTrue($this->redis->set('kbeck', 'Kate Beckinsale'));
        $stars = $this->redis->mGet(['tswift', 'millaj', 'nonexisting']);
        $this->assertContains('Taylor Swift', $stars);
        $this->assertContains('Milla Jovovich', $stars);
        $this->assertContains(false, $stars);
        $this->assertSame(['Taylor Swift', 'Milla Jovovich', false], $stars);
        $this->assertEquals(['Taylor Swift', 'Milla Jovovich', false], $stars);
        // Cleanup used keys
        $this->assertEquals(3, $this->redis->delete(['tswift', 'millaj', 'kbeck']));
    }

    /** @test */
    public function redis_strings_getmultiple()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete(['tswift', 'millaj', 'kbeck']));
        $this->assertTrue($this->redis->set('tswift', 'Taylor Swift'));
        $this->assertTrue($this->redis->set('millaj', 'Milla Jovovich'));
        $this->assertTrue($this->redis->set('kbeck', 'Kate Beckinsale'));
        $stars = $this->redis->getMultiple(['tswift', 'millaj', 'kbeck']);
        $this->assertContains('Taylor Swift', $stars);
        $this->assertContains('Milla Jovovich', $stars);
        $this->assertContains('Kate Beckinsale', $stars);
        $this->assertSame(['Taylor Swift', 'Milla Jovovich', 'Kate Beckinsale'], $stars);
        $this->assertEquals(['Taylor Swift', 'Milla Jovovich', 'Kate Beckinsale'], $stars);
        // Cleanup used keys
        $this->assertEquals(3, $this->redis->delete(['tswift', 'millaj', 'kbeck']));
    }

    /** @test */
    public function redis_strings_getmultiple_nonexisting()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete(['tswift', 'millaj', 'kbeck']));
        $this->assertTrue($this->redis->set('tswift', 'Taylor Swift'));
        $this->assertTrue($this->redis->set('millaj', 'Milla Jovovich'));
        $this->assertTrue($this->redis->set('kbeck', 'Kate Beckinsale'));
        $stars = $this->redis->getMultiple(['tswift', 'millaj', 'nonexisting']);
        $this->assertContains('Taylor Swift', $stars);
        $this->assertContains('Milla Jovovich', $stars);
        $this->assertContains(false, $stars);
        $this->assertSame(['Taylor Swift', 'Milla Jovovich', false], $stars);
        $this->assertEquals(['Taylor Swift', 'Milla Jovovich', false], $stars);
        // Cleanup used keys
        $this->assertEquals(3, $this->redis->delete(['tswift', 'millaj', 'kbeck']));
    }

    /** @test */
    public function redis_strings_mset_single_key_value()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete(['tswift']));
        $this->assertTrue($this->redis->mSet(['tswift' => 'Taylor Swift']));
        $this->assertEquals('Taylor Swift', $this->redis->get('tswift'));
        // Cleanup used keys
        $this->assertEquals(1, $this->redis->delete(['tswift']));
    }

    /** @test */
    public function redis_strings_mset_non_associative_array()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete(['tswift']));
        $this->expectException(NotAssociativeArrayException::class);
        $this->assertTrue($this->redis->mSet(['tswift', 'Taylor Swift']));
        $this->assertEquals('Taylor Swift', $this->redis->get('tswift'));
        // Cleanup used keys
        $this->assertEquals(1, $this->redis->delete(['tswift']));
    }

    /** @test */
    public function redis_strings_mset_multiple_key_value()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete(['tswift', 'millaj']));
        $this->assertTrue($this->redis->mSet(['tswift' => 'Taylor Swift', 'millaj' => 'Milla Jovovich']));
        $this->assertEquals('Taylor Swift', $this->redis->get('tswift'));
        $this->assertEquals('Milla Jovovich', $this->redis->get('millaj'));
        // Cleanup used keys
        $this->assertEquals(2, $this->redis->delete(['tswift', 'millaj']));
    }

    /** @test */
    public function redis_strings_msetnx_single_key_value()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete(['tswift']));
        $this->assertTrue($this->redis->mSetNX(['tswift' => 'Taylor Swift']));
        $this->assertEquals('Taylor Swift', $this->redis->get('tswift'));
        // Cleanup used keys
        $this->assertEquals(1, $this->redis->delete(['tswift']));
    }

    /** @test */
    public function redis_strings_msetnx_existing_single_key_value()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete(['tswift']));
        $this->assertTrue($this->redis->set('tswift', 'Taylor Swift'));
        $this->assertFalse($this->redis->mSetNX(['tswift' => 'Taylor Swift']));
        $this->assertEquals('Taylor Swift', $this->redis->get('tswift'));
        // Cleanup used keys
        $this->assertEquals(1, $this->redis->delete(['tswift']));
    }

    /** @test */
    public function redis_strings_msetnx_non_associative_array()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete(['tswift']));
        $this->expectException(NotAssociativeArrayException::class);
        $this->assertTrue($this->redis->mSetNX(['tswift', 'Taylor Swift']));
        $this->assertEquals('Taylor Swift', $this->redis->get('tswift'));
        // Cleanup used keys
        $this->assertEquals(1, $this->redis->delete(['tswift']));
    }

    /** @test */
    public function redis_strings_msetnx_multiple_key_value()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete(['tswift', 'millaj']));
        $this->assertTrue($this->redis->mSetNX(['tswift' => 'Taylor Swift', 'millaj' => 'Milla Jovovich']));
        $this->assertEquals('Taylor Swift', $this->redis->get('tswift'));
        $this->assertEquals('Milla Jovovich', $this->redis->get('millaj'));
        // Cleanup used keys
        $this->assertEquals(2, $this->redis->delete(['tswift', 'millaj']));
    }

    /** @test */
    public function redis_strings_msetnx_existing_multiple_key_value()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete(['tswift', 'millaj']));
        $this->assertTrue($this->redis->set('tswift', 'Taylor Swift'));
        $this->assertFalse($this->redis->mSetNX(['tswift' => 'Taylor Swift', 'millaj' => 'Milla Jovovich']));
        $this->assertEquals('Taylor Swift', $this->redis->get('tswift'));
        // Cleanup used keys
        $this->assertEquals(1, $this->redis->delete(['tswift', 'millaj']));
    }
}
