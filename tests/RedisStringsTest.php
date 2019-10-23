<?php

namespace Webdcg\Redis\Tests;

use Webdcg\Redis\Redis;
use PHPUnit\Framework\TestCase;

class RedisStringsTest extends TestCase
{
    protected $redis;

    protected function setUp(): void
    {
        $this->redis = new Redis;
        $this->redis->connect();
    }

    /** @test */
    public function redis_strings_set()
    {
        // Simple key -> value set
        $this->assertTrue($this->redis->set('key', 'value'));
        // Will redirect, and actually make an SETEX call
        $this->assertTrue($this->redis->set('key', 'value', 10));
        // Will set the key, if it doesn't exist, with a ttl of 10 seconds
        $this->assertTrue($this->redis->set('key:'.time(), 'value', ['nx', 'ex' => 10]));
        $this->assertFalse($this->redis->set('key:'.time(), 'value', ['nx', 'ex' => 10]));
        // Will set a key, if it does exist, with a ttl of 1000 miliseconds
        $this->assertTrue($this->redis->set('key', 'value', ['xx', 'px' => 1000]));
    }
}
