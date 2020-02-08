<?php

namespace Webdcg\Redis\Tests;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Process\PhpProcess;
use Webdcg\Redis\Redis;

class bzPopMinTest extends TestCase
{
    protected $redis;
    protected $key;
    protected $keyOptional;
    protected $producer;

    protected function setUp(): void
    {
        $this->redis = new Redis();
        $this->redis->connect();
        $this->redis->setOption(\Redis::OPT_SERIALIZER, \Redis::SERIALIZER_NONE);
        $this->key = 'SortedSets:bzPopMin';
        $this->keyOptional = 'SortedSets:bzPopMin:Optional';
    }


    /*
     * ========================================================================
     * bzPopMin
     *
     * Redis | Sorted Sets | bzPopMin => Block until Redis can pop the lowest scoring members from one or more ZSETs.
     * ========================================================================
     */


    /** @test */
    public function redis_sorted_sets_bzPopMin()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $this->produceKeyValue();
        // --------------------  T E S T  --------------------
        $this->assertEquals([$this->key, 'A', 1.1], $this->redis->bzPopMin([$this->key], 1));
        $this->assertEquals(1, $this->redis->exists($this->key));
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
    protected function produceKeyValue()
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
