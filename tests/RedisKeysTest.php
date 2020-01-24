<?php

namespace Webdcg\Redis\Tests;

use PHPUnit\Framework\TestCase;
use Webdcg\Redis\Redis;

class RedisKeysTest extends TestCase
{
    protected $redis;
    protected $backup;
    protected $key;
    protected $keyOptional;

    protected function setUp(): void
    {
        $this->redis = new Redis();
        $this->redis->connect();
        $this->redis->setOption(\Redis::OPT_SERIALIZER, \Redis::SERIALIZER_NONE);
        $this->key = 'Keys';
        $this->keyOptional = 'KeysOptional';
    }

    /** @test */
    public function redis_keys_renamenx_nonexisting_key()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->keyOptional));
        $this->assertEquals(0, $this->redis->exists($this->key));
        // --------------------  T E S T  --------------------
        $this->assertFalse($this->redis->renameNx($this->key, $this->keyOptional));
        // --------------------  T E S T  --------------------
        $this->assertEquals(0, $this->redis->exists($this->key));
        $this->assertEquals(0, $this->redis->exists($this->keyOptional));
    }

    /** @test */
    public function redis_keys_renamenx_existing_key()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->keyOptional));
        $this->assertEquals(0, $this->redis->exists($this->key));
        $this->assertEquals(0, $this->redis->exists($this->keyOptional));
        $this->assertTrue($this->redis->set($this->key, 'value'));
        $this->assertTrue($this->redis->set($this->keyOptional, 'value'));
        $this->assertEquals(1, $this->redis->exists($this->key));
        $this->assertEquals(1, $this->redis->exists($this->keyOptional));
        // --------------------  T E S T  --------------------
        $this->assertFalse($this->redis->renameNx($this->key, $this->keyOptional));
        // --------------------  T E S T  --------------------
        $this->assertEquals(1, $this->redis->exists($this->key));
        $this->assertEquals(1, $this->redis->exists($this->keyOptional));
        $this->assertEquals('value', $this->redis->get($this->keyOptional));
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->keyOptional));
    }

    /** @test */
    public function redis_keys_renamenx_single_key()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->keyOptional));
        $this->assertEquals(0, $this->redis->exists($this->key));
        $this->assertEquals(0, $this->redis->exists($this->keyOptional));
        $this->assertTrue($this->redis->set($this->key, 'value'));
        $this->assertEquals(1, $this->redis->exists($this->key));
        $this->assertEquals(0, $this->redis->exists($this->keyOptional));
        // --------------------  T E S T  --------------------
        $this->assertTrue($this->redis->renameNx($this->key, $this->keyOptional));
        // --------------------  T E S T  --------------------
        $this->assertEquals(0, $this->redis->exists($this->key));
        $this->assertEquals(1, $this->redis->exists($this->keyOptional));
        $this->assertEquals('value', $this->redis->get($this->keyOptional));
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->keyOptional));
    }

    /** @test */
    public function redis_keys_renamekey_nonexisting_key()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->keyOptional));
        $this->assertEquals(0, $this->redis->exists($this->key));
        // --------------------  T E S T  --------------------
        $this->assertFalse($this->redis->renameKey($this->key, $this->keyOptional));
        // --------------------  T E S T  --------------------
        $this->assertEquals(0, $this->redis->exists($this->key));
        $this->assertEquals(0, $this->redis->exists($this->keyOptional));
    }

    /** @test */
    public function redis_keys_renamekey_key()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->keyOptional));
        $this->assertEquals(0, $this->redis->exists($this->key));
        $this->assertTrue($this->redis->set($this->key, 'value'));
        $this->assertEquals(1, $this->redis->exists($this->key));
        // --------------------  T E S T  --------------------
        $this->assertTrue($this->redis->renameKey($this->key, $this->keyOptional));
        // --------------------  T E S T  --------------------
        $this->assertEquals(0, $this->redis->exists($this->key));
        $this->assertEquals(1, $this->redis->exists($this->keyOptional));
        $this->assertEquals('value', $this->redis->get($this->keyOptional));
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->keyOptional));
    }

    /** @test */
    public function redis_keys_rename_nonexisting_key()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->keyOptional));
        $this->assertEquals(0, $this->redis->exists($this->key));
        // --------------------  T E S T  --------------------
        $this->assertFalse($this->redis->rename($this->key, $this->keyOptional));
        // --------------------  T E S T  --------------------
        $this->assertEquals(0, $this->redis->exists($this->key));
        $this->assertEquals(0, $this->redis->exists($this->keyOptional));
    }

    /** @test */
    public function redis_keys_rename_key()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->keyOptional));
        $this->assertEquals(0, $this->redis->exists($this->key));
        $this->assertTrue($this->redis->set($this->key, 'value'));
        $this->assertEquals(1, $this->redis->exists($this->key));
        // --------------------  T E S T  --------------------
        $this->assertTrue($this->redis->rename($this->key, $this->keyOptional));
        // --------------------  T E S T  --------------------
        $this->assertEquals(0, $this->redis->exists($this->key));
        $this->assertEquals(1, $this->redis->exists($this->keyOptional));
        $this->assertEquals('value', $this->redis->get($this->keyOptional));
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->keyOptional));
    }

    /** @test */
    public function redis_keys_randomkey()
    {
        $this->key = array_map(function ($item) {
            return "Key{$item}";
        }, range(1, 5));

        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));

        foreach ($this->key as $key) {
            $this->assertEquals(0, $this->redis->exists($key));
            $this->assertTrue($this->redis->set($key, 'value'));
        }

        foreach ($this->key as $key) {
            $this->assertEquals(1, $this->redis->exists($key));
            // --------------------  T E S T  --------------------
            $this->assertContains($this->redis->randomKey(), $this->key);
            // --------------------  T E S T  --------------------
        }

        $this->assertGreaterThanOrEqual(5, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_keys_pttl_nonexisting_key()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $this->assertEquals(0, $this->redis->exists($this->key));
        // --------------------  T E S T  --------------------
        $this->assertEquals(-2, $this->redis->pttl($this->key));
    }

    /** @test */
    public function redis_keys_pttl_single_key()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $this->assertTrue($this->redis->set($this->key, 'value'));
        $this->assertTrue($this->redis->expire($this->key, 1));
        usleep(10 * 1000);
        // --------------------  T E S T  --------------------
        $this->assertGreaterThanOrEqual(985, $this->redis->pttl($this->key));
        // Cleanup used keys
        $this->assertEquals(1, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_keys_ttl_nonexisting_key()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $this->assertEquals(0, $this->redis->exists($this->key));
        $this->assertEquals(-2, $this->redis->ttl($this->key));
    }

    /** @test */
    public function redis_keys_ttl_single_key()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $this->assertTrue($this->redis->set($this->key, 'value'));
        $this->assertTrue($this->redis->expire($this->key, 2));
        // --------------------  T E S T  --------------------
        $this->assertEquals(2, $this->redis->ttl($this->key));
        // Cleanup used keys
        $this->assertEquals(1, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_keys_persist_nonexisting_key()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $this->assertEquals(0, $this->redis->exists($this->key));
        $this->assertEquals(-2, $this->redis->ttl($this->key));
        // --------------------  T E S T  --------------------
        $this->assertFalse($this->redis->persist($this->key));
    }

    /** @test */
    public function redis_keys_persist_noexpiration_key()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $this->assertTrue($this->redis->set($this->key, 'value'));
        $this->assertEquals(1, $this->redis->exists($this->key));
        $this->assertEquals(-1, $this->redis->ttl($this->key));
        // --------------------  T E S T  --------------------
        $this->assertFalse($this->redis->persist($this->key));
        // Cleanup used keys
        $this->assertEquals(1, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_keys_persist_single_key()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $this->assertTrue($this->redis->set($this->key, 'value'));
        $this->assertTrue($this->redis->expire($this->key, 1));
        $this->assertEquals(1, $this->redis->exists($this->key));
        // --------------------  T E S T  --------------------
        $this->assertTrue($this->redis->persist($this->key));
        // --------------------  T E S T  --------------------
        $this->assertEquals(-1, $this->redis->ttl($this->key));
        // Cleanup used keys
        $this->assertEquals(1, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_keys_object_nonexisting_key()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $this->assertFalse($this->redis->object('encoding', $this->key));
        $this->assertFalse($this->redis->object('refcount', $this->key));
        $this->assertFalse($this->redis->object('idletime', $this->key));
    }

    /** @test */
    public function redis_keys_object_single_key()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $this->assertTrue($this->redis->set($this->key, 'value'));
        $this->assertEquals('embstr', $this->redis->object('encoding', $this->key));
        $this->assertEquals(1, $this->redis->object('refcount', $this->key));
        $this->assertEquals(0, $this->redis->object('idletime', $this->key));
        // Cleanup used keys
        $this->assertEquals(1, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_keys_move_single_key()
    {
        // Start from scratch
        $this->assertTrue($this->redis->select(1));
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $this->assertTrue($this->redis->select(0));
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        // Create a key on DB 0
        $this->assertTrue($this->redis->set($this->key, 'value'));
        $this->assertEquals('value', $this->redis->get($this->key));
        $this->assertEquals(1, $this->redis->exists($this->key));
        // Move to DB 1
        // --------------------  T E S T  --------------------
        $this->assertTrue($this->redis->move($this->key, 1));
        // --------------------  T E S T  --------------------
        $this->assertEquals(0, $this->redis->exists($this->key));
        // Verify that is on the correct place
        $this->assertTrue($this->redis->select(1));
        $this->assertEquals(1, $this->redis->exists($this->key));
        $this->assertEquals('value', $this->redis->get($this->key));
        // Cleanup used keys
        $this->assertEquals(1, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_keys_move_nonexisting_key()
    {
        // Start from scratch
        $this->assertTrue($this->redis->select(0));
        $this->assertGreaterThanOrEqual(0, $this->redis->delete('nonexisting'));
        $this->assertEquals(0, $this->redis->exists('nonexisting'));
        // Move to DB 1
        // --------------------  T E S T  --------------------
        $this->assertFalse($this->redis->move('nonexisting', 1));
        // Verify that is on the correct place
        $this->assertTrue($this->redis->select(1));
        $this->assertEquals(0, $this->redis->exists('nonexisting'));
    }

    /** @test */
    public function redis_keys_migrate_single_key()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        // Create a key and move it to a different Server
        $this->assertTrue($this->redis->set($this->key, 'value'));
        // --------------------  T E S T  --------------------
        $this->assertTrue($this->redis->migrate('127.0.0.1', 6381, [$this->key], 0, 3600));
        // Checking on the backup Server
        $this->backup = new Redis();
        $this->assertTrue($this->backup->connect('127.0.0.1', 6381));
        $this->assertEquals(1, $this->backup->exists($this->key));
        $this->assertEquals('value', $this->backup->get($this->key));
        // Cleanup used keys
        $this->assertEquals(1, $this->backup->delete($this->key));
    }

    /** @test */
    public function redis_keys_migrate_nonexisting_key()
    {
        $this->assertEquals(0, $this->redis->exists('nonexisting'));
        // --------------------  T E S T  --------------------
        $this->assertTrue($this->redis->migrate('127.0.0.1', 6381, ['nonexisting'], 0, 3600));
        // Checking on the backup Server
        $this->backup = new Redis();
        $this->assertTrue($this->backup->connect('127.0.0.1', 6381));
        $this->assertEquals(0, $this->backup->exists('nonexisting'));
    }

    /** @test */
    public function redis_keys_scan_defaults()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        // Simple key -> value set
        $this->assertTrue($this->redis->set($this->key, 'value'));
        /* Without enabling Redis::SCAN_RETRY (default condition) */
        $it = null;
        do {
            // Scan for some keys
            // --------------------  T E S T  --------------------
            $array_keys = $this->redis->scan($it);
            // Redis may return empty results, so protect against that
            if ($array_keys !== false) {
                $this->assertContains($this->key, $array_keys);
            }
        } while ($it > 0);
        // Cleanup used keys
        $this->assertEquals(1, $this->redis->delete($this->key));
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
        // --------------------  T E S T  --------------------
        $this->assertEquals(2, $this->redis->del('key1', 'key2'));
        $this->assertEquals(0, $this->redis->exists(['key1', 'key2']));
    }

    /** @test */
    public function redis_keys_del_array_keys()
    {
        $this->assertTrue($this->redis->set('key1', 'val1'));
        $this->assertTrue($this->redis->set('key2', 'val2'));
        // --------------------  T E S T  --------------------
        $this->assertEquals(2, $this->redis->del(['key1', 'key2']));
        $this->assertEquals(0, $this->redis->exists(['key1', 'key2']));
    }

    /** @test */
    public function redis_keys_delete_single_key()
    {
        $this->assertTrue($this->redis->set('key1', 'val1'));
        // --------------------  T E S T  --------------------
        $this->assertEquals(1, $this->redis->delete('key1'));
        $this->assertEquals(0, $this->redis->exists('key1'));
    }

    /** @test */
    public function redis_keys_delete_multiple_keys()
    {
        $this->assertTrue($this->redis->set('key1', 'val1'));
        $this->assertTrue($this->redis->set('key2', 'val2'));
        // --------------------  T E S T  --------------------
        $this->assertEquals(2, $this->redis->delete('key1', 'key2'));
        $this->assertEquals(0, $this->redis->exists(['key1', 'key2']));
    }

    /** @test */
    public function redis_keys_delete_array_keys()
    {
        $this->assertTrue($this->redis->set('key1', 'val1'));
        $this->assertTrue($this->redis->set('key2', 'val2'));
        // --------------------  T E S T  --------------------
        $this->assertEquals(2, $this->redis->delete(['key1', 'key2']));
        $this->assertEquals(0, $this->redis->exists(['key1', 'key2']));
    }

    /** @test */
    public function redis_keys_unlink_single_key()
    {
        $this->assertTrue($this->redis->set('key1', 'val1'));
        // --------------------  T E S T  --------------------
        $this->assertEquals(1, $this->redis->unlink('key1'));
        $this->assertEquals(0, $this->redis->exists('key1'));
    }

    /** @test */
    public function redis_keys_unlink_multiple_keys()
    {
        $this->assertTrue($this->redis->set('key1', 'val1'));
        $this->assertTrue($this->redis->set('key2', 'val2'));
        // --------------------  T E S T  --------------------
        $this->assertEquals(2, $this->redis->unlink('key1', 'key2'));
        $this->assertEquals(0, $this->redis->exists(['key1', 'key2']));
    }

    /** @test */
    public function redis_keys_unlink_array_keys()
    {
        $this->assertTrue($this->redis->set('key1', 'val1'));
        $this->assertTrue($this->redis->set('key2', 'val2'));
        // --------------------  T E S T  --------------------
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
    public function redis_keys_expire_keys()
    {
        $this->assertTrue($this->redis->set('key1', 'value'));
        $this->assertTrue($this->redis->set('key2', 'value'));
        $this->assertTrue($this->redis->set('key3', 'value'));

        $this->assertTrue($this->redis->expire('key1', 1));
        $this->assertEquals(1, $this->redis->exists('key1'));

        $this->assertTrue($this->redis->setTimeout('key2', 1));
        $this->assertEquals(1, $this->redis->exists('key2'));

        $this->assertTrue($this->redis->expireAt('key3', time() + 1));
        $this->assertEquals(1, $this->redis->exists('key3'));

        usleep(1.1 * 1000000);

        $this->assertEquals(0, $this->redis->exists('key1'));
        $this->assertEquals(0, $this->redis->exists('key2'));
        $this->assertEquals(0, $this->redis->exists('key3'));
    }

    /** Combine in a single test for all the expirations */
    public function redis_keys_expire_single_key()
    {
        $this->assertTrue($this->redis->set('key', 'value'));
        $this->assertTrue($this->redis->expire('key', 1));
        $this->assertEquals(1, $this->redis->exists('key'));
        usleep(1.1 * 1000000);
        $this->assertEquals(0, $this->redis->exists('key'));
    }

    /** @test */
    public function redis_keys_expire_non_existing_key()
    {
        $this->assertFalse($this->redis->expire('NonExistingKey', 1));
        $this->assertEquals(0, $this->redis->exists('NonExistingKey'));
    }

    /** Combine in a single test for all the expirations */
    public function redis_keys_set_timeout_single_key()
    {
        $this->assertTrue($this->redis->set('key', 'value'));
        $this->assertTrue($this->redis->setTimeout('key', 1));
        $this->assertEquals(1, $this->redis->exists('key'));
        usleep(1.1 * 1000000);
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
        $this->assertTrue($this->redis->pexpire('key', 10));
        $this->assertEquals(1, $this->redis->exists('key'));
        usleep(20 * 1000);
        $this->assertEquals(0, $this->redis->exists('key'));
    }

    /** @test */
    public function redis_keys_pexpire_non_existing_key()
    {
        $this->assertFalse($this->redis->pexpire('NonExistingKey', 1));
        $this->assertEquals(0, $this->redis->exists('NonExistingKey'));
    }

    /** Combine in a single test for all the expirations */
    public function redis_keys_expire_at_single_key()
    {
        $this->assertTrue($this->redis->set('key', 'value'));
        $currentTime = time();
        $this->assertTrue($this->redis->expireAt('key', $currentTime + 1));
        $this->assertEquals(1, $this->redis->exists('key'));
        usleep(1.1 * 1000000);
        $this->assertEquals(0, $this->redis->exists('key'));
    }

    /** @test */
    public function redis_keys_expire_at_non_existing_key()
    {
        $this->assertFalse($this->redis->expireAt('NonExistingKey', 1));
        $this->assertEquals(0, $this->redis->exists('NonExistingKey'));
    }

    /** @test */
    public function redis_keys_pexpire_at_single_key()
    {
        $this->assertTrue($this->redis->set('key', 'value'));
        [$usec, $sec] = explode(' ', microtime());
        $currentTime = floor(((float) $usec + (float) $sec) * 1000);
        $this->assertTrue($this->redis->pexpireAt('key', $currentTime + 10));
        $this->assertEquals(1, $this->redis->exists('key'));
        usleep(20 * 1000);
        $this->assertEquals(0, $this->redis->exists('key'));
    }

    /** @test */
    public function redis_keys_pexpire_at_non_existing_key()
    {
        $this->assertFalse($this->redis->pexpireAt('NonExistingKey', 1));
        $this->assertEquals(0, $this->redis->exists('NonExistingKey'));
    }

    /** @test */
    public function redis_keys_find_keys()
    {
        // Creating 3 keys
        $this->assertTrue($this->redis->set('key1', 'value'));
        $this->assertTrue($this->redis->set('key2', 'value'));
        $this->assertTrue($this->redis->set('key3', 'value'));

        // Fetching all existing keys with key prefix
        $keys = $this->redis->keys('key*');
        $this->assertIsArray($keys);
        $this->assertCount(3, $keys);
        $this->assertContains('key1', $keys);
        $this->assertContains('key2', $keys);
        $this->assertContains('key3', $keys);

        // Removing the first key created
        $this->assertEquals(1, $this->redis->delete(['key1']));

        // Verify that we only have 2 remaining keys for the same pattern
        $keys = $this->redis->keys('key*');
        $this->assertCount(2, $keys);
        $this->assertIsArray($keys);
        $this->assertContains('key2', $keys);
        $this->assertContains('key3', $keys);

        // Cleanup
        $this->assertEquals(2, $this->redis->delete(['key2', 'key3']));
    }

    /** @test */
    public function redis_keys_find_non_existing_keys()
    {
        $keys = $this->redis->keys('yek*');
        $this->assertIsArray($keys);
        $this->assertCount(0, $keys);
        $this->assertEmpty($keys);
    }

    /** @test */
    public function redis_keys_find_getkeys()
    {
        // Creating 3 keys
        $this->assertTrue($this->redis->set('key1', 'value'));
        $this->assertTrue($this->redis->set('key2', 'value'));
        $this->assertTrue($this->redis->set('key3', 'value'));

        // Fetching all existing keys with key prefix
        $keys = $this->redis->getKeys('key*');
        $this->assertIsArray($keys);
        $this->assertCount(3, $keys);
        $this->assertContains('key1', $keys);
        $this->assertContains('key2', $keys);
        $this->assertContains('key3', $keys);

        // Removing the first key created
        $this->assertEquals(1, $this->redis->delete(['key1']));

        // Verify that we only have 2 remaining keys for the same pattern
        $keys = $this->redis->getKeys('key*');
        $this->assertCount(2, $keys);
        $this->assertIsArray($keys);
        $this->assertContains('key2', $keys);
        $this->assertContains('key3', $keys);

        // Cleanup
        $this->assertEquals(2, $this->redis->delete(['key2', 'key3']));
    }

    /** @test */
    public function redis_keys_find_non_existing_getkeys()
    {
        $keys = $this->redis->getKeys('yek*');
        $this->assertIsArray($keys);
        $this->assertCount(0, $keys);
        $this->assertEmpty($keys);
    }
}
