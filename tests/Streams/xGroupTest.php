<?php

namespace Webdcg\Redis\Tests;

use PHPUnit\Framework\TestCase;
use Webdcg\Redis\Redis;

class xGroupTest extends TestCase
{
    protected $redis;
    protected $key;
    protected $keyOptional;
    protected $group;
    protected $consumer;
    protected $producer;

    protected function setUp(): void
    {
        $this->redis = new Redis();
        $this->redis->connect();
        $this->redis->setOption(\Redis::OPT_SERIALIZER, \Redis::SERIALIZER_NONE);
        $this->key = 'Streams:xGroupTest';
        $this->keyOptional = $this->key . ':Optional';
        $this->group = $this->key . ':Group';
        $this->consumer = $this->key . ':Consumer';
    }

    protected function tearDown(): void
    {
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
    }


    /*
     * ========================================================================
     * xGroup
     *
     * Redis | Streams | xGroup => This command is used in order to create, destroy, or manage consumer groups.
     * ========================================================================
     */


    /** @test */
    public function redis_streams_xGroup_Create()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));

        $this->assertTrue($this->redis->xGroup('CREATE', $this->key, $this->group, 0, true));

        $expected = (int) floor(microtime(true) * 1000) - 1;
        $messageId = $this->redis->xAdd($this->key, '*', ['key' => 'value']);
        $this->assertGreaterThanOrEqual($expected, explode('-', $messageId)[0]);

        $messages = [
            $this->key => [
                $messageId => ['key' => 'value']
            ]
        ];

        $xReadGroup = $this->redis->xReadGroup($this->group, $this->consumer, [$this->key => '>']);
        $this->assertIsIterable($xReadGroup);
        $this->assertIsArray($xReadGroup);
        $this->assertEquals($messages, $xReadGroup);
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
    }


    /** @test */
    public function redis_streams_xGroup_SetId()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));

        $this->assertTrue($this->redis->xGroup('CREATE', $this->key, $this->group, 0, true));
        $this->assertTrue($this->redis->xGroup('SETID', $this->key, $this->group, 0));

        $expected = (int) floor(microtime(true) * 1000) - 1;
        $messageId = $this->redis->xAdd($this->key, '*', ['key' => 'value']);
        $this->assertGreaterThanOrEqual($expected, explode('-', $messageId)[0]);

        $messages = [
            $this->key => [
                $messageId => ['key' => 'value']
            ]
        ];

        $xReadGroup = $this->redis->xReadGroup($this->group, $this->consumer, [$this->key => '>']);
        $this->assertIsIterable($xReadGroup);
        $this->assertIsArray($xReadGroup);
        $this->assertEquals($messages, $xReadGroup);
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
    }


    /** @test */
    public function redis_streams_xGroup_Help()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));

        $this->assertTrue($this->redis->xGroup('CREATE', $this->key, $this->group, 0, true));
        $this->assertTrue($this->redis->xGroup('SETID', $this->key, $this->group, 0));

        $expected = (int) floor(microtime(true) * 1000) - 1;
        $messageId = $this->redis->xAdd($this->key, '*', ['key' => 'value']);
        $this->assertGreaterThanOrEqual($expected, explode('-', $messageId)[0]);

        $messages = [
            $this->key => [
                $messageId => ['key' => 'value']
            ]
        ];

        $help = $this->redis->xGroup('HELP');
        $this->assertIsIterable($help);
        $this->assertIsArray($help);

        $xReadGroup = $this->redis->xReadGroup($this->group, $this->consumer, [$this->key => '>']);
        $this->assertIsIterable($xReadGroup);
        $this->assertIsArray($xReadGroup);
        $this->assertEquals($messages, $xReadGroup);
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
    }


    /** @test */
    public function redis_streams_xGroup_Destroy()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));

        $this->assertTrue($this->redis->xGroup('CREATE', $this->key, $this->group, 0, true));
        $this->assertTrue($this->redis->xGroup('SETID', $this->key, $this->group, 0));

        $expected = (int) floor(microtime(true) * 1000) - 1;
        $messageId = $this->redis->xAdd($this->key, '*', ['key' => 'value']);
        $this->assertGreaterThanOrEqual($expected, explode('-', $messageId)[0]);

        $destroy = $this->redis->xGroup('DESTROY', $this->key, $this->group);
        $this->assertIsScalar($destroy);
        $this->assertIsNumeric($destroy);
        $this->assertIsInt($destroy);
        $this->assertEquals(1, $destroy);
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
    }


    /** @test */
    public function redis_streams_xGroup_DelConsumer()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));

        $this->assertTrue($this->redis->xGroup('CREATE', $this->key, $this->group, 0, true));
        $this->assertTrue($this->redis->xGroup('SETID', $this->key, $this->group, 0));

        $expected = (int) floor(microtime(true) * 1000) - 1;
        $messageId = $this->redis->xAdd($this->key, '*', ['key' => 'value']);
        $this->assertGreaterThanOrEqual($expected, explode('-', $messageId)[0]);

        $messages = [
            $this->key => [
                $messageId => ['key' => 'value']
            ]
        ];

        $xReadGroup = $this->redis->xReadGroup($this->group, $this->consumer, [$this->key => '>']);
        $this->assertIsIterable($xReadGroup);
        $this->assertIsArray($xReadGroup);
        $this->assertEquals($messages, $xReadGroup);

        $delConsumer = $this->redis->xGroup('DELCONSUMER', $this->key, $this->group, $this->consumer);
        $this->assertIsScalar($delConsumer);
        $this->assertIsNumeric($delConsumer);
        $this->assertIsInt($delConsumer);
        $this->assertEquals(1, $delConsumer);

        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
    }
}
