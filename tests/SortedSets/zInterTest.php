<?php

namespace Webdcg\Redis\Tests;

use PHPUnit\Framework\TestCase;
use Webdcg\Redis\Exceptions\SetOperationException;
use Webdcg\Redis\Redis;

class zInterTest extends TestCase
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
        $this->key = 'SortedSets:zInter';
        $this->keyOptional = 'SortedSets:zInter:Optional';
    }

    /*
     * ========================================================================
     * zInter
     * ========================================================================
     */

    /** @test */
    public function redis_sorted_sets_zinter_aggregate_max()
    {
        $destinationKey = $this->key . ':' . $this->keyOptional;
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->keyOptional));
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($destinationKey));

        $this->assertEquals(1, $this->redis->zAdd($this->key, 1.0, 'A'));
        $this->assertEquals(1, $this->redis->zAdd($this->key, 2.5, 'B'));
        $this->assertEquals(2, $this->redis->zCard($this->key));

        $this->assertEquals(1, $this->redis->zAdd($this->keyOptional, 2.0, 'B'));
        $this->assertEquals(1, $this->redis->zAdd($this->keyOptional, 3.0, 'C'));
        $this->assertEquals(2, $this->redis->zCard($this->keyOptional));

        // T E S T  -----------------------------------------------------------
        $this->assertEquals(1, $this->redis->zInter($destinationKey, [$this->key, $this->keyOptional], [1, 1], 'MAX'));
        $this->assertEquals(1, $this->redis->zCard($destinationKey));

        // Remove all the keys used
        $this->assertEquals(1, $this->redis->delete($this->key));
        $this->assertEquals(1, $this->redis->delete($this->keyOptional));
        $this->assertEquals(1, $this->redis->delete($destinationKey));
    }

    /** @test */
    public function redis_sorted_sets_zinter_aggregate_min()
    {
        $destinationKey = $this->key . ':' . $this->keyOptional;
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->keyOptional));
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($destinationKey));

        $this->assertEquals(1, $this->redis->zAdd($this->key, 1.0, 'A'));
        $this->assertEquals(1, $this->redis->zAdd($this->key, 2.5, 'B'));
        $this->assertEquals(2, $this->redis->zCard($this->key));

        $this->assertEquals(1, $this->redis->zAdd($this->keyOptional, 2.0, 'B'));
        $this->assertEquals(1, $this->redis->zAdd($this->keyOptional, 3.0, 'C'));
        $this->assertEquals(2, $this->redis->zCard($this->keyOptional));

        // T E S T  -----------------------------------------------------------
        $this->assertEquals(1, $this->redis->zInter($destinationKey, [$this->key, $this->keyOptional], [1, 1], 'MIN'));
        $this->assertEquals(1, $this->redis->zCard($destinationKey));

        // Remove all the keys used
        $this->assertEquals(1, $this->redis->delete($this->key));
        $this->assertEquals(1, $this->redis->delete($this->keyOptional));
        $this->assertEquals(1, $this->redis->delete($destinationKey));
    }

    /** @test */
    public function redis_sorted_sets_zinter_aggregate_sum()
    {
        $destinationKey = $this->key . ':' . $this->keyOptional;
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->keyOptional));
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($destinationKey));

        $this->assertEquals(1, $this->redis->zAdd($this->key, 1.0, 'A'));
        $this->assertEquals(1, $this->redis->zAdd($this->key, 2.0, 'B'));
        $this->assertEquals(2, $this->redis->zCard($this->key));

        $this->assertEquals(1, $this->redis->zAdd($this->keyOptional, 2.0, 'B'));
        $this->assertEquals(1, $this->redis->zAdd($this->keyOptional, 3.0, 'C'));
        $this->assertEquals(2, $this->redis->zCard($this->keyOptional));

        // T E S T  -----------------------------------------------------------
        $this->assertEquals(1, $this->redis->zInter($destinationKey, [$this->key, $this->keyOptional], [1, 1], 'SUM'));
        $this->assertEquals(1, $this->redis->zCard($destinationKey));

        // Remove all the keys used
        $this->assertEquals(1, $this->redis->delete($this->key));
        $this->assertEquals(1, $this->redis->delete($this->keyOptional));
        $this->assertEquals(1, $this->redis->delete($destinationKey));
    }

    /** @test */
    public function redis_sorted_sets_zinter_multiply_weights()
    {
        $destinationKey = $this->key . ':' . $this->keyOptional;
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->keyOptional));
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($destinationKey));

        $this->assertEquals(1, $this->redis->zAdd($this->key, 1.0, 'A'));
        $this->assertEquals(1, $this->redis->zAdd($this->key, 2.0, 'B'));
        $this->assertEquals(2, $this->redis->zCard($this->key));

        $this->assertEquals(1, $this->redis->zAdd($this->keyOptional, 2.0, 'B'));
        $this->assertEquals(1, $this->redis->zAdd($this->keyOptional, 3.0, 'C'));
        $this->assertEquals(2, $this->redis->zCard($this->keyOptional));

        // T E S T  -----------------------------------------------------------
        $this->assertEquals(1, $this->redis->zInter($destinationKey, [$this->key, $this->keyOptional], [2, 4]));
        $this->assertEquals(1, $this->redis->zCard($destinationKey));

        // Remove all the keys used
        $this->assertEquals(1, $this->redis->delete($this->key));
        $this->assertEquals(1, $this->redis->delete($this->keyOptional));
        $this->assertEquals(1, $this->redis->delete($destinationKey));
    }

    /** @test */
    public function redis_sorted_sets_zinter_with_weights()
    {
        $destinationKey = $this->key . ':' . $this->keyOptional;
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->keyOptional));
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($destinationKey));

        $this->assertEquals(1, $this->redis->zAdd($this->key, 1.0, 'A'));
        $this->assertEquals(1, $this->redis->zAdd($this->key, 2.0, 'B'));
        $this->assertEquals(2, $this->redis->zCard($this->key));

        $this->assertEquals(1, $this->redis->zAdd($this->keyOptional, 2.0, 'B'));
        $this->assertEquals(1, $this->redis->zAdd($this->keyOptional, 3.0, 'C'));
        $this->assertEquals(2, $this->redis->zCard($this->keyOptional));

        // T E S T  -----------------------------------------------------------
        $this->assertEquals(1, $this->redis->zInter($destinationKey, [$this->key, $this->keyOptional], [1, 1]));
        $this->assertEquals(1, $this->redis->zCard($destinationKey));

        // Remove all the keys used
        $this->assertEquals(1, $this->redis->delete($this->key));
        $this->assertEquals(1, $this->redis->delete($this->keyOptional));
        $this->assertEquals(1, $this->redis->delete($destinationKey));
    }

    /** @test */
    public function redis_sorted_sets_zinter_simple_intersection()
    {
        $destinationKey = $this->key . ':' . $this->keyOptional;
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->keyOptional));
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($destinationKey));

        $this->assertEquals(1, $this->redis->zAdd($this->key, 1.0, 'A'));
        $this->assertEquals(1, $this->redis->zAdd($this->key, 2.0, 'B'));
        $this->assertEquals(2, $this->redis->zCard($this->key));

        $this->assertEquals(1, $this->redis->zAdd($this->keyOptional, 2.0, 'B'));
        $this->assertEquals(1, $this->redis->zAdd($this->keyOptional, 3.0, 'C'));
        $this->assertEquals(2, $this->redis->zCard($this->keyOptional));

        // T E S T  -----------------------------------------------------------
        $this->assertEquals(1, $this->redis->zInter($destinationKey, [$this->key, $this->keyOptional]));
        $this->assertEquals(1, $this->redis->zCard($destinationKey));

        // Remove all the keys used
        $this->assertEquals(1, $this->redis->delete($this->key));
        $this->assertEquals(1, $this->redis->delete($this->keyOptional));
        $this->assertEquals(1, $this->redis->delete($destinationKey));
    }

    /** @test */
    public function redis_sorted_sets_zinter_wrong_operation()
    {
        $destinationKey = $this->key . ':' . $this->keyOptional;
        // T E S T  -----------------------------------------------------------
        $this->expectException(SetOperationException::class);
        $this->redis->zInter($destinationKey, [$this->key, $this->keyOptional], [1, 1], 'AVG');
    }
}
