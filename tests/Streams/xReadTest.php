<?php

namespace Webdcg\Redis\Tests;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Process\PhpProcess;
use Webdcg\Redis\Redis;

class xReadTest extends TestCase
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
        $this->key = 'Streams:xReadTest';
        $this->keyOptional = $this->key . ':Optional';
        $this->group = $this->key . ':Group';
    }

    protected function tearDown(): void
    {
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
    }


    /*
     * ========================================================================
     * xRead
     *
     * Redis | Sorted Sets | xRead => Read data from one or more streams and only return IDs greater than sent in the command.
     * ========================================================================
     */


    /** @test */
    public function redis_streams_xRead_single_Stream()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $expected = (int) floor(microtime(true) * 1000) - 1;
        $messageId = $this->redis->xAdd($this->key, '*', ['key' => 'value']);
        $this->assertGreaterThanOrEqual($expected, explode('-', $messageId)[0]);

        $messages = [
            $this->key => [
                $messageId => ['key' => 'value']
            ]
        ];

        $xRead = $this->redis->xRead([$this->key => $expected . '-0']);
        $this->assertIsIterable($xRead);
        $this->assertIsArray($xRead);
        $this->assertEquals($messages, $xRead);
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
    }


    /** @test */
    public function redis_streams_xRead_multiple_Streams()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->keyOptional));

        $expected = (int) floor(microtime(true) * 1000) - 1;

        $messageId = $this->redis->xAdd($this->key, '*', ['key' => 'value']);
        $messageId2 = $this->redis->xAdd($this->keyOptional, '*', ['key' => 'value']);
        $this->assertGreaterThanOrEqual($expected, explode('-', $messageId)[0]);
        $this->assertGreaterThanOrEqual($expected, explode('-', $messageId2)[0]);

        $messages = [
            $this->key => [
                $messageId => ['key' => 'value']
            ],
            $this->keyOptional => [
                $messageId2 => ['key' => 'value']
            ],
        ];

        $xRead = $this->redis->xRead([
            $this->key => $expected . '-0',
            $this->keyOptional => $expected . '-0',
        ]);

        $this->assertIsIterable($xRead);
        $this->assertIsArray($xRead);
        $this->assertEquals($messages, $xRead);

        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->keyOptional));
    }


    /** @test */
    public function redis_streams_xRange_multiple_count()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $expected = (int) floor(microtime(true) * 1000) - 1;
        $total = random_int(6, 10);
        
        for ($i = 0; $i < $total; $i++) {
            $messageId = $this->redis->xAdd($this->key, '*', ['key' => $i]);
            $messages[$messageId] = ['key' => $i];
            $this->assertGreaterThanOrEqual($expected, explode('-', $messageId)[0]);
        }

        $messages = [
            $this->key => array_slice($messages, 0, 5, true),
        ];
        $xRead = $this->redis->xRead([$this->key => $expected . '-0'], 5);

        $this->assertIsIterable($xRead);
        $this->assertIsArray($xRead);
        $this->assertEquals($messages, $xRead);
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
    }


    /** @test */
    public function redis_streams_xRead_Blocking_Stream()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $expected = (int) floor(microtime(true) * 1000) - 1;
        $this->produceStreamEvents(10);
        $xRead = $this->redis->xRead([$this->key => $expected . '-0'], 2, 0);
        $this->assertIsIterable($xRead);
        $this->assertIsArray($xRead);
        $this->assertEquals(2, count($xRead[$this->key]));
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
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
    protected function produceStreamEvents(int $count = 1)
    {
        $script = <<<EOF
<?php
    require __DIR__ . '/vendor/autoload.php';
    use Webdcg\Redis\Redis;
    \$redis = new Redis();
    \$redis->connect();
    usleep(1000 * random_int(50, 100));
    for (\$i = 0; \$i < {$count}; \$i++) {
        \$redis->xAdd('{$this->key}', '*', ['key' => \$i]);
        usleep(1000 * random_int(5, 50));
    }
EOF;
        $this->producer = new PhpProcess($script);
        $this->producer->run();
    }
}
