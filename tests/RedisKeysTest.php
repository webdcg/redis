<?php

namespace Webdcg\Redis\Tests;

use PHPUnit\Framework\TestCase;
use Webdcg\Redis\Redis;

class RedisKeysTest extends TestCase
{
    protected $redis;

    protected function setUp(): void
    {
        $this->redis = new Redis;
        $this->redis->connect();
        $this->redis->setOption(\Redis::OPT_SERIALIZER, \Redis::SERIALIZER_NONE);
    }

    /** @test */
    public function redis_keys_del_single_key()
    {
        $this->assertTrue($this->redis->set('key1', 'val1'));
        $this->assertEquals(1, $this->redis->del('key1'));
        $this->assertEquals(0, $this->redis->exists('key1'));
    }

    /** @test */
    public function redis_keys_del_multiple_keys()
    {
        $this->assertTrue($this->redis->set('key1', 'val1'));
        $this->assertTrue($this->redis->set('key2', 'val2'));
        $this->assertEquals(2, $this->redis->del('key1', 'key2'));
        $this->assertEquals(0, $this->redis->exists(['key1', 'key2']));
    }

    /** @test */
    public function redis_keys_del_array_keys()
    {
        $this->assertTrue($this->redis->set('key1', 'val1'));
        $this->assertTrue($this->redis->set('key2', 'val2'));
        $this->assertEquals(2, $this->redis->del(['key1', 'key2']));
        $this->assertEquals(0, $this->redis->exists(['key1', 'key2']));
    }

    /** @test */
    public function redis_keys_delete_single_key()
    {
        $this->assertTrue($this->redis->set('key1', 'val1'));
        $this->assertEquals(1, $this->redis->delete('key1'));
        $this->assertEquals(0, $this->redis->exists('key1'));
    }

    /** @test */
    public function redis_keys_delete_multiple_keys()
    {
        $this->assertTrue($this->redis->set('key1', 'val1'));
        $this->assertTrue($this->redis->set('key2', 'val2'));
        $this->assertEquals(2, $this->redis->delete('key1', 'key2'));
        $this->assertEquals(0, $this->redis->exists(['key1', 'key2']));
    }

    /** @test */
    public function redis_keys_delete_array_keys()
    {
        $this->assertTrue($this->redis->set('key1', 'val1'));
        $this->assertTrue($this->redis->set('key2', 'val2'));
        $this->assertEquals(2, $this->redis->delete(['key1', 'key2']));
        $this->assertEquals(0, $this->redis->exists(['key1', 'key2']));
    }

    /** @test */
    public function redis_keys_unlink_single_key()
    {
        $this->assertTrue($this->redis->set('key1', 'val1'));
        $this->assertEquals(1, $this->redis->unlink('key1'));
        $this->assertEquals(0, $this->redis->exists('key1'));
    }

    /** @test */
    public function redis_keys_unlink_multiple_keys()
    {
        $this->assertTrue($this->redis->set('key1', 'val1'));
        $this->assertTrue($this->redis->set('key2', 'val2'));
        $this->assertEquals(2, $this->redis->unlink('key1', 'key2'));
        $this->assertEquals(0, $this->redis->exists(['key1', 'key2']));
    }

    /** @test */
    public function redis_keys_unlink_array_keys()
    {
        $this->assertTrue($this->redis->set('key1', 'val1'));
        $this->assertTrue($this->redis->set('key2', 'val2'));
        $this->assertEquals(2, $this->redis->unlink(['key1', 'key2']));
        $this->assertEquals(0, $this->redis->exists(['key1', 'key2']));
    }

    /** @test */
    public function redis_keys_dump_non_existing()
    {
        $this->assertFalse($this->redis->dump('NonExistingKey'));
        $this->assertEquals(0, $this->redis->exists('NonExistingKey'));
    }

    /** @test */
    public function redis_keys_dump_string()
    {
        $this->assertTrue($this->redis->set('string', 'value'));
        $this->assertNotEmpty($this->redis->dump('string'));
        $this->assertEquals(1, $this->redis->delete('string'));
        $this->assertEquals(0, $this->redis->exists('string'));
    }

    /** @test */
    public function redis_keys_dump_integer()
    {
        $this->assertTrue($this->redis->set('integer', 1));
        $this->assertNotEmpty($this->redis->dump('integer'));
        $this->assertEquals(1, $this->redis->delete('integer'));
        $this->assertEquals(0, $this->redis->exists('integer'));
    }

    /** @test */
    public function redis_keys_dump_boolean()
    {
        $this->assertTrue($this->redis->set('boolean', true));
        $this->assertNotEmpty($this->redis->dump('boolean'));
        $this->assertEquals(1, $this->redis->delete('boolean'));
        $this->assertEquals(0, $this->redis->exists('boolean'));
    }

    /** @test */
    public function redis_keys_dump_float()
    {
        $this->assertTrue($this->redis->set('float', pi()));
        $this->assertNotEmpty($this->redis->dump('float'));
        $this->assertEquals(1, $this->redis->delete('float'));
        $this->assertEquals(0, $this->redis->exists('float'));
    }

    /** @test */
    public function redis_keys_exists_single_key()
    {
        $this->assertTrue($this->redis->set('key1', 'val1'));
        $this->assertEquals(1, $this->redis->exists('key1'));
        $this->assertEquals(1, $this->redis->delete('key1'));
        $this->assertEquals(0, $this->redis->exists('key1'));
    }

    /** @test */
    public function redis_keys_exists_multiple_keys()
    {
        $this->assertTrue($this->redis->set('key1', 'val1'));
        $this->assertTrue($this->redis->set('key2', 'val2'));
        $this->assertEquals(2, $this->redis->exists('key1', 'key2'));
        $this->assertEquals(2, $this->redis->delete('key1', 'key2'));
        $this->assertEquals(0, $this->redis->exists('key1', 'key2'));
    }

    /** @test */
    public function redis_keys_exists_array_keys()
    {
        $this->assertTrue($this->redis->set('key1', 'val1'));
        $this->assertTrue($this->redis->set('key2', 'val2'));
        $this->assertEquals(2, $this->redis->exists(['key1', 'key2']));
        $this->assertEquals(2, $this->redis->delete(['key1', 'key2']));
        $this->assertEquals(0, $this->redis->exists(['key1', 'key2']));
    }

    /** @test */
    public function redis_keys_exists_missing_keys()
    {
        $this->assertTrue($this->redis->set('key1', 'val1'));
        $this->assertEquals(1, $this->redis->exists(['key1', 'NonExistingKey']));
        $this->assertEquals(1, $this->redis->delete(['key1']));
        $this->assertEquals(0, $this->redis->exists(['key1', 'NonExistingKey']));
    }

    /** @test */
    public function redis_keys_exists_non_existing_keys()
    {
        $this->assertEquals(0, $this->redis->exists('NonExistingKey'));
    }

    /** @test */
    public function redis_keys_expire_single_key()
    {
        $this->assertTrue($this->redis->set('key', 'value'));
        $this->assertTrue($this->redis->expire('key', 1));
        $this->assertEquals(1, $this->redis->exists('key'));
        sleep(1);
        $this->assertEquals(0, $this->redis->exists('key'));
    }

    /** @test */
    public function redis_keys_expire_non_existing_key()
    {
        $this->assertFalse($this->redis->expire('NonExistingKey', 1));
        $this->assertEquals(0, $this->redis->exists('NonExistingKey'));
    }

    /** @test */
    public function redis_keys_set_timeout_single_key()
    {
        $this->assertTrue($this->redis->set('key', 'value'));
        $this->assertTrue($this->redis->setTimeout('key', 1));
        $this->assertEquals(1, $this->redis->exists('key'));
        sleep(1);
        $this->assertEquals(0, $this->redis->exists('key'));
    }

    /** @test */
    public function redis_keys_set_timeout_non_existing_key()
    {
        $this->assertFalse($this->redis->setTimeout('NonExistingKey', 1));
        $this->assertEquals(0, $this->redis->exists('NonExistingKey'));
    }

    /** @test */
    public function redis_keys_expire_miliseconds_single_key()
    {
        $this->assertTrue($this->redis->set('key', 'value'));
        $this->assertTrue($this->redis->pexpire('key', 100));
        $this->assertEquals(1, $this->redis->exists('key'));
        usleep(110 * 1000);
        $this->assertEquals(0, $this->redis->exists('key'));
    }

    /** @test */
    public function redis_keys_pexpire_non_existing_key()
    {
        $this->assertFalse($this->redis->pexpire('NonExistingKey', 1));
        $this->assertEquals(0, $this->redis->exists('NonExistingKey'));
    }
}
