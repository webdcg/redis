<?php

namespace Webdcg\Redis\Tests;

use PHPUnit\Framework\TestCase;
use Webdcg\Redis\Exceptions\SetOperationException;
use Webdcg\Redis\Redis;

class zUnionTest extends TestCase
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
        $this->key = 'SortedSets:zUnion';
        $this->keyOptional = 'SortedSets:zUnion:Optional';
    }


    /*
     * ========================================================================
     * zUnion
     *
     * Redis | Sorted Sets | zUnion => Creates an union of sorted sets given in second argument. The result of the union will be stored in the sorted set defined by the first argument.
     * ========================================================================
     */


    /** @test */
    public function redis_sorted_sets_zUnion_aggregate_max()
    {
        $destinationKey = $this->key . ':' . $this->keyOptional;
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->keyOptional));

        $this->assertEquals(1, $this->redis->zAdd($this->key, 1.0, 'A'));
        $this->assertEquals(1, $this->redis->zAdd($this->key, 2.5, 'B'));
        $this->assertEquals(2, $this->redis->zCard($this->key));

        $this->assertEquals(1, $this->redis->zAdd($this->keyOptional, 2.0, 'B'));
        $this->assertEquals(1, $this->redis->zAdd($this->keyOptional, 3.0, 'C'));
        $this->assertEquals(2, $this->redis->zCard($this->keyOptional));

        // T E S T  -----------------------------------------------------------
        $this->assertEquals(3, $this->redis->zUnion($destinationKey, [$this->key, $this->keyOptional], [1, 1], 'MAX'));
        $this->assertEquals(3, $this->redis->zCard($destinationKey));

        // Remove all the keys used
        $this->assertEquals(1, $this->redis->delete($this->key));
        $this->assertEquals(1, $this->redis->delete($this->keyOptional));
        $this->assertEquals(1, $this->redis->delete($destinationKey));
    }


    /** @test */
    public function redis_sorted_sets_zUnion_aggregate_min()
    {
        $destinationKey = $this->key . ':' . $this->keyOptional;
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->keyOptional));

        $this->assertEquals(1, $this->redis->zAdd($this->key, 1.0, 'A'));
        $this->assertEquals(1, $this->redis->zAdd($this->key, 2.5, 'B'));
        $this->assertEquals(2, $this->redis->zCard($this->key));

        $this->assertEquals(1, $this->redis->zAdd($this->keyOptional, 2.0, 'B'));
        $this->assertEquals(1, $this->redis->zAdd($this->keyOptional, 3.0, 'C'));
        $this->assertEquals(2, $this->redis->zCard($this->keyOptional));

        // T E S T  -----------------------------------------------------------
        $this->assertEquals(3, $this->redis->zUnion($destinationKey, [$this->key, $this->keyOptional], [1, 1], 'MIN'));
        $this->assertEquals(3, $this->redis->zCard($destinationKey));

        // Remove all the keys used
        $this->assertEquals(1, $this->redis->delete($this->key));
        $this->assertEquals(1, $this->redis->delete($this->keyOptional));
        $this->assertEquals(1, $this->redis->delete($destinationKey));
    }


    /** @test */
    public function redis_sorted_sets_zUnion_aggregate_sum()
    {
        $destinationKey = $this->key . ':' . $this->keyOptional;
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->keyOptional));

        $this->assertEquals(1, $this->redis->zAdd($this->key, 1.0, 'A'));
        $this->assertEquals(1, $this->redis->zAdd($this->key, 2.0, 'B'));
        $this->assertEquals(2, $this->redis->zCard($this->key));

        $this->assertEquals(1, $this->redis->zAdd($this->keyOptional, 2.0, 'B'));
        $this->assertEquals(1, $this->redis->zAdd($this->keyOptional, 3.0, 'C'));
        $this->assertEquals(2, $this->redis->zCard($this->keyOptional));

        // T E S T  -----------------------------------------------------------
        $this->assertEquals(3, $this->redis->zUnion($destinationKey, [$this->key, $this->keyOptional], [1, 1], 'SUM'));
        $this->assertEquals(3, $this->redis->zCard($destinationKey));

        // Remove all the keys used
        $this->assertEquals(1, $this->redis->delete($this->key));
        $this->assertEquals(1, $this->redis->delete($this->keyOptional));
        $this->assertEquals(1, $this->redis->delete($destinationKey));
    }


    /** @test */
    public function redis_sorted_sets_zUnion_multiply_weights()
    {
        $destinationKey = $this->key . ':' . $this->keyOptional;
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->keyOptional));

        $this->assertEquals(1, $this->redis->zAdd($this->key, 1.0, 'A'));
        $this->assertEquals(1, $this->redis->zAdd($this->key, 2.0, 'B'));
        $this->assertEquals(2, $this->redis->zCard($this->key));

        $this->assertEquals(1, $this->redis->zAdd($this->keyOptional, 2.0, 'B'));
        $this->assertEquals(1, $this->redis->zAdd($this->keyOptional, 3.0, 'C'));
        $this->assertEquals(2, $this->redis->zCard($this->keyOptional));

        // T E S T  -----------------------------------------------------------
        $this->assertEquals(3, $this->redis->zUnion($destinationKey, [$this->key, $this->keyOptional], [2, 4]));
        $this->assertEquals(3, $this->redis->zCard($destinationKey));

        // Remove all the keys used
        $this->assertEquals(1, $this->redis->delete($this->key));
        $this->assertEquals(1, $this->redis->delete($this->keyOptional));
        $this->assertEquals(1, $this->redis->delete($destinationKey));
    }


    /** @test */
    public function redis_sorted_sets_zUnion_with_weights()
    {
        $destinationKey = $this->key . ':' . $this->keyOptional;
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->keyOptional));

        $this->assertEquals(1, $this->redis->zAdd($this->key, 1.0, 'A'));
        $this->assertEquals(1, $this->redis->zAdd($this->key, 2.0, 'B'));
        $this->assertEquals(2, $this->redis->zCard($this->key));

        $this->assertEquals(1, $this->redis->zAdd($this->keyOptional, 2.0, 'B'));
        $this->assertEquals(1, $this->redis->zAdd($this->keyOptional, 3.0, 'C'));
        $this->assertEquals(2, $this->redis->zCard($this->keyOptional));

        // T E S T  -----------------------------------------------------------
        $this->assertEquals(3, $this->redis->zUnion($destinationKey, [$this->key, $this->keyOptional], [1, 1]));
        $this->assertEquals(3, $this->redis->zCard($destinationKey));

        // Remove all the keys used
        $this->assertEquals(1, $this->redis->delete($this->key));
        $this->assertEquals(1, $this->redis->delete($this->keyOptional));
        $this->assertEquals(1, $this->redis->delete($destinationKey));
    }


    /** @test */
    public function redis_sorted_sets_zUnion_simple_intersection()
    {
        $destinationKey = $this->key . ':' . $this->keyOptional;
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->keyOptional));

        $this->assertEquals(1, $this->redis->zAdd($this->key, 1.0, 'A'));
        $this->assertEquals(1, $this->redis->zAdd($this->key, 2.0, 'B'));
        $this->assertEquals(2, $this->redis->zCard($this->key));

        $this->assertEquals(1, $this->redis->zAdd($this->keyOptional, 2.0, 'B'));
        $this->assertEquals(1, $this->redis->zAdd($this->keyOptional, 3.0, 'C'));
        $this->assertEquals(2, $this->redis->zCard($this->keyOptional));

        // T E S T  -----------------------------------------------------------
        $this->assertEquals(3, $this->redis->zUnion($destinationKey, [$this->key, $this->keyOptional]));
        $this->assertEquals(3, $this->redis->zCard($destinationKey));

        // Remove all the keys used
        $this->assertEquals(1, $this->redis->delete($this->key));
        $this->assertEquals(1, $this->redis->delete($this->keyOptional));
        $this->assertEquals(1, $this->redis->delete($destinationKey));
    }


    /** @test */
    public function redis_sorted_sets_zUnion_wrong_operation()
    {
        $destinationKey = $this->key . ':' . $this->keyOptional;
        // T E S T  -----------------------------------------------------------
        $this->expectException(SetOperationException::class);
        $this->redis->zUnion($destinationKey, [$this->key, $this->keyOptional], [1, 1], 'AVG');
    }
}
