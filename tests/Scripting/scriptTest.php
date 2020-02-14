<?php

namespace Webdcg\Redis\Tests;

use PHPUnit\Framework\TestCase;
use Webdcg\Redis\Exceptions\ScriptCommandException;
use Webdcg\Redis\Redis;

class scriptTest extends TestCase
{
    protected $redis;
    protected $key;
    protected $keyOptional;
    protected $producer;
    protected $sha1;

    protected $copyKey = <<<EOF
local s = KEYS[1]
local d = KEYS[2] 
redis.call("RESTORE", d, 0, redis.call("DUMP", s))
return {"OK"}
EOF;

    protected $slowCopyKey = <<<EOF
local posix = require 'posix'
local s = KEYS[1]
local d = KEYS[2] 
posix.sleep(1)
redis.call("RESTORE", d, 0, redis.call("DUMP", s))
return {"OK"}
EOF;

    protected function setUp(): void
    {
        $this->redis = new Redis();
        $this->redis->connect();
        $this->redis->setOption(\Redis::OPT_SERIALIZER, \Redis::SERIALIZER_NONE);
        $this->key = 'Scripting:script';
        $this->keyOptional = 'Scripting:script:Optional';
    }

    /*
     * ========================================================================
     * script
     *
     * Redis | Scripting | script => Execute the Redis SCRIPT command to perform various operations on the scripting subsystem.
     * ========================================================================
     */


    /** @test */
    public function redis_Scripting_script_invalid_command()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $this->expectException(ScriptCommandException::class);
        $this->assertEquals(1, $this->redis->script('return'));
    }


    /** @test */
    public function redis_Scripting_script_load()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $this->sha1 = sha1($this->copyKey);
        $this->assertEquals($this->sha1, $this->redis->script('load', $this->copyKey));
        $this->assertTrue($this->redis->script('flush'));
    }


    /** @test */
    public function redis_Scripting_script_load_run()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->keyOptional));
        $this->sha1 = sha1($this->copyKey);
        $this->assertEquals($this->sha1, $this->redis->script('load', $this->copyKey));
        $this->assertTrue($this->redis->set($this->key, 'value'));
        $this->assertEquals('value', $this->redis->get($this->key));
        $this->assertEquals(['OK'], $this->redis->evalSha($this->sha1, [$this->key, $this->keyOptional], 2));
        $this->assertEquals('value', $this->redis->get($this->keyOptional));
        // Start from scratch
        $this->assertTrue($this->redis->script('flush'));
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->keyOptional));
    }


    /** @test */
    public function redis_Scripting_script_flush()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $this->sha1 = sha1($this->copyKey);
        $this->assertEquals($this->sha1, $this->redis->script('load', $this->copyKey));
        $this->assertTrue($this->redis->script('flush'));
    }


    /** @test */
    public function redis_Scripting_script_exists()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $this->sha1 = sha1($this->copyKey);
        $this->assertEquals([0], $this->redis->script('exists', $this->sha1));
        $this->assertEquals($this->sha1, $this->redis->script('load', $this->copyKey));
        $this->assertEquals([1], $this->redis->script('exists', $this->sha1));
        $this->assertTrue($this->redis->script('flush'));
        $this->assertEquals([0], $this->redis->script('exists', $this->sha1));
    }


    /** @test */
    public function redis_Scripting_script_kill()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->keyOptional));
        $this->sha1 = sha1($this->slowCopyKey);
        // dump($this->sha1);
        $this->assertEquals($this->sha1, $this->redis->script('load', $this->slowCopyKey));
        $this->assertTrue($this->redis->set($this->key, 'value'));
        $this->assertEquals('value', $this->redis->get($this->key));
        
        // $this->assertEquals(['OK'], $this->redis->evalSha($this->sha1, [$this->key, $this->keyOptional], 2));
        // $this->assertEquals('value', $this->redis->get($this->keyOptional));

        // Cleanup
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->keyOptional));
    }

    /**
    * ========================================================================
    * H E L P E R   M E T H O D S
    * ========================================================================
    */


    /**
     * Using the Symfony Process component, we connect to Redis and create
     * a single element on a Queue.
     * See: https://symfony.com/doc/current/index.html#gsc.tab=0.
     *
     * @return
     */
    protected function runSlowCopyKey()
    {
        $script = <<<EOF
<?php
    require __DIR__ . '/vendor/autoload.php';
    use Webdcg\Redis\Redis;
    \$redis = new Redis();
    \$redis->connect();
    usleep(1000 * random_int(50, 100));
    \$redis->zAdd('{$this->key}', 2.2, 'B');
    \$redis->zAdd('{$this->key}', 1.1, 'A');
EOF;
        $this->producer = new PhpProcess($script);
        $this->producer->run();
    }
}
