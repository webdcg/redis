<?php

namespace Webdcg\Redis\Tests;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Process\PhpProcess;
use Webdcg\Redis\Redis;

class RedisPubSubTest extends TestCase
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
        $this->key = 'PubSub';
        $this->keyOptional = 'PubSub:Optional';
    }

    /** @test */
    public function redis_PubSub_publish_single()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $this->assertEquals(0, $this->redis->publish($this->key, 'Hello Redis..! => '.time()));
    }


    /** @test */
    public function redis_PubSub_subscribe_single()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $this->produceMessage();

        $reply = $this->redis->subscribe([$this->key]);

        // $this->assertEquals(0, $this->redis->publish($this->key, 'Hello Redis..!'));
        
        // $redis = new \Redis();
        // $redis->connect('localhost');
        // $response = $redis->subscribe([$this->key]);
        // dump($response);
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
    protected function produceMessage()
    {
        $script = <<<EOF
<?php
    require __DIR__ . '/vendor/autoload.php';
    use Webdcg\Redis\Redis;
    \$redis = new Redis();
    \$redis->connect();
    usleep(1000 * random_int(250, 500));
    \$redis->publish('{$this->key}', 'Hello Redis..! => '.time());
    usleep(1000 * random_int(50, 100));
    \$redis->publish('{$this->key}', 'quit');
EOF;
        $this->producer = new PhpProcess($script);
        $this->producer->run();
    }
}
