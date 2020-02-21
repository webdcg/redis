<?php

namespace Webdcg\Redis\Tests;

use PHPUnit\Framework\TestCase;
use Webdcg\Redis\Redis;

class xInfoTest extends TestCase
{
    protected $redis;
    protected $key;
    protected $keyOptional;
    protected $group;
    protected $producer;

    protected function setUp(): void
    {
        $this->redis = new Redis();
        $this->redis->connect();
        $this->redis->setOption(\Redis::OPT_SERIALIZER, \Redis::SERIALIZER_NONE);
        $this->key = 'Streams:xInfoTest';
        $this->keyOptional = $this->key . ':Optional';
        $this->group = $this->key . ':Group';
    }

    protected function tearDown(): void
    {
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
    }


    /*
     * ========================================================================
     * xInfo
     *
     * Redis | Sorted Sets | xInfo => Get information about a stream or consumer groups.
     * ========================================================================
     */


    /** @test */
    public function redis_streams_xInfo_Stream_Bad_Command()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $expected = (int) floor(microtime(true) * 1000) - 1;
        $messageId = $this->redis->xAdd($this->key, '*', ['key' => microtime()]);
        $this->assertGreaterThanOrEqual($expected, explode('-', $messageId)[0]);
        $this->expectException(\Exception::class);
        $this->assertFalse($this->redis->xInfo('HELP!'));
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_streams_xInfo_Stream_Help()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $expected = (int) floor(microtime(true) * 1000) - 1;
        $messageId = $this->redis->xAdd($this->key, '*', ['key' => microtime()]);
        $this->assertGreaterThanOrEqual($expected, explode('-', $messageId)[0]);
        $this->assertFalse($this->redis->xInfo('HELP'));
        $this->assertFalse($this->redis->xInfo('help'));
        $this->assertFalse($this->redis->xInfo('Help'));
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_streams_xInfo_Stream()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $expected = (int) floor(microtime(true) * 1000) - 1;
        $value = (string) microtime(true);
        $messageId = $this->redis->xAdd($this->key, '*', ['key' => $value]);
        $this->assertGreaterThanOrEqual($expected, explode('-', $messageId)[0]);
        $stream = [
            'length' => 1,
            'radix-tree-keys' => 1,
            'radix-tree-nodes' => 2,
            'groups' => 0,
            'last-generated-id' => $messageId,
            'first-entry' => [
                $messageId => [
                    'key' => $value
                ]
            ],
            'last-entry' => [
                $messageId => [
                    'key' => $value
                ]
            ]
        ];
        $xInfo = $this->redis->xInfo('STREAM', $this->key);
        $this->assertIsIterable($xInfo);
        $this->assertIsArray($xInfo);
        $this->assertEquals($stream, $xInfo);
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_streams_xInfo_Groups()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $expected = (int) floor(microtime(true) * 1000) - 1;
        $value = (string) microtime(true);
        $messageId = $this->redis->xAdd($this->key, '*', ['key' => $value]);
        $this->assertGreaterThanOrEqual($expected, explode('-', $messageId)[0]);
        $this->assertTrue($this->redis->xGroup('CREATE', $this->key, $this->group, 0, true));

        $xInfo = $this->redis->xInfo('GROUPS', $this->key);
        $groups = [ 0 => [
            'name' => $this->group,
            'consumers' => 0,
            'pending' => 0,
            'last-delivered-id' => '0-0',
        ]];

        $this->assertIsIterable($xInfo);
        $this->assertIsArray($xInfo);
        $this->assertEquals($groups, $xInfo);
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_streams_xInfo_Consumers()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $expected = (int) floor(microtime(true) * 1000) - 1;
        $value = (string) microtime(true);
        $messageId = $this->redis->xAdd($this->key, '*', ['key' => $value]);
        $this->assertGreaterThanOrEqual($expected, explode('-', $messageId)[0]);
        $this->assertTrue($this->redis->xGroup('CREATE', $this->key, $this->group, 0, true));

        $xInfo = $this->redis->xInfo('CONSUMERS', $this->key, $this->group);
        $this->assertIsIterable($xInfo);
        $this->assertIsArray($xInfo);
        $this->assertEquals([], $xInfo);

        // ToDo: Add consumers
        $xInfo = $this->redis->xInfo('CONSUMERS', $this->key, $this->group);
        // dump($xInfo);
        // $consumers = [];

        $this->assertIsIterable($xInfo);
        $this->assertIsArray($xInfo);
        // $this->assertEquals($groups, $xInfo);
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
    }
}
