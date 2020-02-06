<?php

namespace Webdcg\Redis\Tests;

use PHPUnit\Framework\TestCase;
use Webdcg\Redis\Exceptions\SetOperationException;
use Webdcg\Redis\Redis;

class RedisSortedSetsTest extends TestCase
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
        $this->key = 'SortedSets';
        $this->keyOptional = 'SortedSets:Optional';
    }

    /** @test */
    public function redis_sorted_sets_zrange_with_scores()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $this->assertEquals(1, $this->redis->zAdd($this->key, 1.1, 'A'));
        $this->assertEquals(1, $this->redis->zAdd($this->key, 2.2, 'B'));
        $this->assertEquals(1, $this->redis->zAdd($this->key, 3.3, 'C'));
        $this->assertEquals(1, $this->redis->zAdd($this->key, 3.3, 'D'));
        $this->assertEquals(4, $this->redis->zCard($this->key));
        // T E S T  -----------------------------------------------------------
        $range = $this->redis->zRange($this->key, 1, 2, true);
        $this->assertIsArray($range);
        $this->assertEquals(2, count($range));
        $this->assertArraySubset(['B' => 2.2], $range);
        $this->assertArraySubset(['C' => 3.3], $range);
        $this->assertArrayNotHasKey('A', $range);
        // Remove all the keys used
        $this->assertEquals(1, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_sorted_sets_zrange_start_end()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $this->assertEquals(1, $this->redis->zAdd($this->key, 1.1, 'A'));
        $this->assertEquals(1, $this->redis->zAdd($this->key, 2.2, 'B'));
        $this->assertEquals(1, $this->redis->zAdd($this->key, 3.3, 'C'));
        $this->assertEquals(1, $this->redis->zAdd($this->key, 3.3, 'D'));
        $this->assertEquals(4, $this->redis->zCard($this->key));
        // T E S T  -----------------------------------------------------------
        $range = $this->redis->zRange($this->key, 1, 2);
        $this->assertIsArray($range);
        $this->assertEquals(2, count($range));
        $this->assertContains('B', $range);
        $this->assertContains('C', $range);
        $this->assertNotContains('A', $range);
        // Remove all the keys used
        $this->assertEquals(1, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_sorted_sets_zrange_start()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $this->assertEquals(1, $this->redis->zAdd($this->key, 1.1, 'A'));
        $this->assertEquals(1, $this->redis->zAdd($this->key, 2.2, 'B'));
        $this->assertEquals(1, $this->redis->zAdd($this->key, 3.3, 'C'));
        $this->assertEquals(3, $this->redis->zCard($this->key));
        // T E S T  -----------------------------------------------------------
        $range = $this->redis->zRange($this->key, 1);
        $this->assertIsArray($range);
        $this->assertEquals(2, count($range));
        $this->assertContains('B', $range);
        $this->assertContains('C', $range);
        $this->assertNotContains('A', $range);
        // Remove all the keys used
        $this->assertEquals(1, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_sorted_sets_zrange_defaults()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $this->assertEquals(1, $this->redis->zAdd($this->key, 1.1, 'A'));
        $this->assertEquals(1, $this->redis->zAdd($this->key, 2.2, 'B'));
        $this->assertEquals(1, $this->redis->zAdd($this->key, 3.3, 'C'));
        $this->assertEquals(3, $this->redis->zCard($this->key));
        // T E S T  -----------------------------------------------------------
        $range = $this->redis->zRange($this->key);
        $this->assertIsArray($range);
        $this->assertEquals(3, count($range));
        $this->assertContains('A', $range);
        $this->assertContains('B', $range);
        $this->assertContains('C', $range);
        $this->assertNotContains('D', $range);
        // Remove all the keys used
        $this->assertEquals(1, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_sorted_sets_zpop_max()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $this->assertEquals(1, $this->redis->zAdd($this->key, 1.1, 'A'));
        $this->assertEquals(1, $this->redis->zAdd($this->key, 2.2, 'B'));
        $this->assertEquals(1, $this->redis->zAdd($this->key, 3.3, 'C'));
        $this->assertEquals(3, $this->redis->zCard($this->key));
        // T E S T  -----------------------------------------------------------
        $max = $this->redis->zPop($this->key, 2, true);
        $this->assertIsArray($max);
        $this->assertArraySubset(['B' => 2.2], $max);
        $this->assertArraySubset(['C' => 3.3], $max);
        $this->assertEquals(1, $this->redis->zCard($this->key));
        // Remove all the keys used
        $this->assertEquals(1, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_sorted_sets_zpopmax_outbounds()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $this->assertEquals(1, $this->redis->zAdd($this->key, 1.1, 'A'));
        $this->assertEquals(1, $this->redis->zAdd($this->key, 2.2, 'B'));
        $this->assertEquals(1, $this->redis->zAdd($this->key, 3.3, 'C'));
        $this->assertEquals(3, $this->redis->zCard($this->key));
        // T E S T  -----------------------------------------------------------
        $max = $this->redis->zPopMax($this->key, 5);
        $this->assertIsArray($max);
        $this->assertArraySubset(['A' => 1.1], $max);
        $this->assertArraySubset(['B' => 2.2], $max);
        $this->assertArraySubset(['C' => 3.3], $max);
        $this->assertEquals(0, $this->redis->zCard($this->key));
        // Remove all the keys used
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_sorted_sets_zpopmax_multiple()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $this->assertEquals(1, $this->redis->zAdd($this->key, 1.1, 'A'));
        $this->assertEquals(1, $this->redis->zAdd($this->key, 2.2, 'B'));
        $this->assertEquals(1, $this->redis->zAdd($this->key, 3.3, 'C'));
        $this->assertEquals(3, $this->redis->zCard($this->key));
        // T E S T  -----------------------------------------------------------
        $max = $this->redis->zPopMax($this->key, 2);
        $this->assertIsArray($max);
        $this->assertArraySubset(['B' => 2.2], $max);
        $this->assertArraySubset(['C' => 3.3], $max);
        $this->assertEquals(1, $this->redis->zCard($this->key));
        // Remove all the keys used
        $this->assertEquals(1, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_sorted_sets_zpopmax_default()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $this->assertEquals(1, $this->redis->zAdd($this->key, 1.1, 'A'));
        $this->assertEquals(1, $this->redis->zAdd($this->key, 2.2, 'B'));
        $this->assertEquals(1, $this->redis->zAdd($this->key, 3.3, 'C'));
        $this->assertEquals(3, $this->redis->zCard($this->key));
        // T E S T  -----------------------------------------------------------
        $max = $this->redis->zPopMax($this->key);
        $this->assertIsArray($max);
        $this->assertEquals(['C' => 3.3], $max);
        $this->assertEquals(2, $this->redis->zCard($this->key));
        // Remove all the keys used
        $this->assertEquals(1, $this->redis->delete($this->key));
    }


    // ================================================================================

    /** @test */
    public function redis_sorted_sets_zpop_min()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $this->assertEquals(1, $this->redis->zAdd($this->key, 1.1, 'A'));
        $this->assertEquals(1, $this->redis->zAdd($this->key, 2.2, 'B'));
        $this->assertEquals(1, $this->redis->zAdd($this->key, 3.3, 'C'));
        $this->assertEquals(3, $this->redis->zCard($this->key));
        // T E S T  -----------------------------------------------------------
        $min = $this->redis->zPop($this->key, 2, false);
        $this->assertIsArray($min);
        $this->assertArraySubset(['A' => 1.1], $min);
        $this->assertArraySubset(['B' => 2.2], $min);
        $this->assertEquals(1, $this->redis->zCard($this->key));
        // Remove all the keys used
        $this->assertEquals(1, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_sorted_sets_zpopmin_outbounds()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $this->assertEquals(1, $this->redis->zAdd($this->key, 1.1, 'A'));
        $this->assertEquals(1, $this->redis->zAdd($this->key, 2.2, 'B'));
        $this->assertEquals(1, $this->redis->zAdd($this->key, 3.3, 'C'));
        $this->assertEquals(3, $this->redis->zCard($this->key));
        // T E S T  -----------------------------------------------------------
        $min = $this->redis->zPopMin($this->key, 5);
        $this->assertIsArray($min);
        $this->assertArraySubset(['A' => 1.1], $min);
        $this->assertArraySubset(['B' => 2.2], $min);
        $this->assertArraySubset(['C' => 3.3], $min);
        $this->assertEquals(0, $this->redis->zCard($this->key));
        // Remove all the keys used
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_sorted_sets_zpopmin_multiple()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $this->assertEquals(1, $this->redis->zAdd($this->key, 1.1, 'A'));
        $this->assertEquals(1, $this->redis->zAdd($this->key, 2.2, 'B'));
        $this->assertEquals(1, $this->redis->zAdd($this->key, 3.3, 'C'));
        $this->assertEquals(3, $this->redis->zCard($this->key));
        // T E S T  -----------------------------------------------------------
        $min = $this->redis->zPopMin($this->key, 2);
        $this->assertIsArray($min);
        $this->assertArraySubset(['A' => 1.1], $min);
        $this->assertArraySubset(['B' => 2.2], $min);
        $this->assertEquals(1, $this->redis->zCard($this->key));
        // Remove all the keys used
        $this->assertEquals(1, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_sorted_sets_zpopmin_default()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $this->assertEquals(1, $this->redis->zAdd($this->key, 1.1, 'A'));
        $this->assertEquals(1, $this->redis->zAdd($this->key, 2.2, 'B'));
        $this->assertEquals(1, $this->redis->zAdd($this->key, 3.3, 'C'));
        $this->assertEquals(3, $this->redis->zCard($this->key));
        // T E S T  -----------------------------------------------------------
        $min = $this->redis->zPopMin($this->key);
        $this->assertIsArray($min);
        $this->assertEquals(['A' => 1.1], $min);
        $this->assertEquals(2, $this->redis->zCard($this->key));
        // Remove all the keys used
        $this->assertEquals(1, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_sorted_sets_zinter_aggregate_max()
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
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->keyOptional));

        $this->assertEquals(1, $this->redis->zAdd($this->key, 10.0, 'A'));
        $this->assertEquals(1, $this->redis->zAdd($this->keyOptional, 5.0, 'B'));
        // T E S T  -----------------------------------------------------------
        $this->expectException(SetOperationException::class);
        $this->redis->zInter($destinationKey, [$this->key, $this->keyOptional], [1, 1], 'AVG');
        // Remove all the keys used
        $this->assertEquals(1, $this->redis->delete($this->key));
        $this->assertEquals(1, $this->redis->delete($this->keyOptional));
    }
    /** @test */
    public function redis_sorted_sets_zinterstore_aggregate_max()
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
        $this->assertEquals(1, $this->redis->zInterStore($destinationKey, [$this->key, $this->keyOptional], [1, 1], 'MAX'));
        $this->assertEquals(1, $this->redis->zCard($destinationKey));

        // Remove all the keys used
        $this->assertEquals(1, $this->redis->delete($this->key));
        $this->assertEquals(1, $this->redis->delete($this->keyOptional));
        $this->assertEquals(1, $this->redis->delete($destinationKey));
    }

    /** @test */
    public function redis_sorted_sets_zinterstore_aggregate_min()
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
        $this->assertEquals(1, $this->redis->zInterStore($destinationKey, [$this->key, $this->keyOptional], [1, 1], 'MIN'));
        $this->assertEquals(1, $this->redis->zCard($destinationKey));

        // Remove all the keys used
        $this->assertEquals(1, $this->redis->delete($this->key));
        $this->assertEquals(1, $this->redis->delete($this->keyOptional));
        $this->assertEquals(1, $this->redis->delete($destinationKey));
    }

    /** @test */
    public function redis_sorted_sets_zinterstore_aggregate_sum()
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
        $this->assertEquals(1, $this->redis->zInterStore($destinationKey, [$this->key, $this->keyOptional], [1, 1], 'SUM'));
        $this->assertEquals(1, $this->redis->zCard($destinationKey));

        // Remove all the keys used
        $this->assertEquals(1, $this->redis->delete($this->key));
        $this->assertEquals(1, $this->redis->delete($this->keyOptional));
        $this->assertEquals(1, $this->redis->delete($destinationKey));
    }

    /** @test */
    public function redis_sorted_sets_zinterstore_multiply_weights()
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
        $this->assertEquals(1, $this->redis->zInterStore($destinationKey, [$this->key, $this->keyOptional], [2, 4]));
        $this->assertEquals(1, $this->redis->zCard($destinationKey));

        // Remove all the keys used
        $this->assertEquals(1, $this->redis->delete($this->key));
        $this->assertEquals(1, $this->redis->delete($this->keyOptional));
        $this->assertEquals(1, $this->redis->delete($destinationKey));
    }

    /** @test */
    public function redis_sorted_sets_zinterstore_with_weights()
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
        $this->assertEquals(1, $this->redis->zInterStore($destinationKey, [$this->key, $this->keyOptional], [1, 1]));
        $this->assertEquals(1, $this->redis->zCard($destinationKey));

        // Remove all the keys used
        $this->assertEquals(1, $this->redis->delete($this->key));
        $this->assertEquals(1, $this->redis->delete($this->keyOptional));
        $this->assertEquals(1, $this->redis->delete($destinationKey));
    }

    /** @test */
    public function redis_sorted_sets_zinterstore_simple_intersection()
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
        $this->assertEquals(1, $this->redis->zInterStore($destinationKey, [$this->key, $this->keyOptional]));
        $this->assertEquals(1, $this->redis->zCard($destinationKey));

        // Remove all the keys used
        $this->assertEquals(1, $this->redis->delete($this->key));
        $this->assertEquals(1, $this->redis->delete($this->keyOptional));
        $this->assertEquals(1, $this->redis->delete($destinationKey));
    }

    /** @test */
    public function redis_sorted_sets_zinterstore_wrong_operation()
    {
        $destinationKey = $this->key . ':' . $this->keyOptional;
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->keyOptional));

        $this->assertEquals(1, $this->redis->zAdd($this->key, 10.0, 'A'));
        $this->assertEquals(1, $this->redis->zAdd($this->keyOptional, 5.0, 'B'));
        // T E S T  -----------------------------------------------------------
        $this->expectException(SetOperationException::class);
        $this->redis->zInterStore($destinationKey, [$this->key, $this->keyOptional], [1, 1], 'AVG');
        // Remove all the keys used
        $this->assertEquals(1, $this->redis->delete($this->key));
        $this->assertEquals(1, $this->redis->delete($this->keyOptional));
    }

    /** @test */
    public function redis_sorted_sets_zincrby_negative()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $this->assertEquals(1, $this->redis->zAdd($this->key, 10.0, 'A'));
        // T E S T  -----------------------------------------------------------
        $this->assertEquals(5.0, $this->redis->zIncrBy($this->key, -5.0, 'A'));
        $this->assertEquals(6.5, $this->redis->zIncrBy($this->key, 1.5, 'A'));
        // Remove all the keys used
        $this->assertEquals(1, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_sorted_sets_zincrby_positive()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $this->assertEquals(1, $this->redis->zAdd($this->key, 1.0, 'A'));
        // T E S T  -----------------------------------------------------------
        $this->assertEquals(2.0, $this->redis->zIncrBy($this->key, 1.0, 'A'));
        $this->assertEquals(3.5, $this->redis->zIncrBy($this->key, 1.5, 'A'));
        // Remove all the keys used
        $this->assertEquals(1, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_sorted_sets_zincrby_missing_member()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $this->assertEquals(1, $this->redis->zAdd($this->key, 1.0, 'A'));
        // T E S T  -----------------------------------------------------------
        $this->assertEquals(1.0, $this->redis->zIncrBy($this->key, 1.0, 'B'));
        // Remove all the keys used
        $this->assertEquals(1, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_sorted_sets_zcount()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $total = random_int(2, 10);
        for ($i = 0; $i < $total; $i++) {
            $this->assertEquals(1, $this->redis->zAdd($this->key, 1.0 * ($i + 1), chr($i + 65)));
        }
        // T E S T  -----------------------------------------------------------
        $this->assertEquals(2, $this->redis->zCount($this->key, 1, 2));
        $this->assertEquals($total, $this->redis->zCount($this->key, 1, $total));
        $this->assertEquals(2, $this->redis->zCount($this->key, '-inf', 2));
        $this->assertEquals($total, $this->redis->zCount($this->key, 1, '+inf'));
        $this->assertEquals($total, $this->redis->zCount($this->key, '-inf', '+inf'));
        // Remove all the keys used
        $this->assertEquals(1, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_sorted_sets_zsize_duplicate()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $this->assertEquals(1, $this->redis->zAdd($this->key, 1.0, 'A'));
        $this->assertEquals(0, $this->redis->zAdd($this->key, 2.0, 'A'));
        // T E S T  -----------------------------------------------------------
        $this->assertEquals(1, $this->redis->zSize($this->key));
        // Remove all the keys used
        $this->assertEquals(1, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_sorted_sets_zsize_multiple()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $total = random_int(1, 10);
        for ($i = 0; $i < $total; $i++) {
            $this->assertEquals(1, $this->redis->zAdd($this->key, 1.1 * $i, chr($i + 65)));
        }
        // T E S T  -----------------------------------------------------------
        $this->assertEquals($total, $this->redis->zSize($this->key));
        // Remove all the keys used
        $this->assertEquals(1, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_sorted_sets_zsize_single()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $this->assertEquals(1, $this->redis->zAdd($this->key, 1.0, 'A'));
        // T E S T  -----------------------------------------------------------
        $this->assertEquals(1, $this->redis->zSize($this->key));
        // Remove all the keys used
        $this->assertEquals(1, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_sorted_sets_zcard_duplicate()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $this->assertEquals(1, $this->redis->zAdd($this->key, 1.0, 'A'));
        $this->assertEquals(0, $this->redis->zAdd($this->key, 2.0, 'A'));
        // T E S T  -----------------------------------------------------------
        $this->assertEquals(1, $this->redis->zCard($this->key));
        // Remove all the keys used
        $this->assertEquals(1, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_sorted_sets_zcard_multiple()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $total = random_int(1, 10);
        for ($i = 0; $i < $total; $i++) {
            $this->assertEquals(1, $this->redis->zAdd($this->key, 1.1 * $i, chr($i + 65)));
        }
        // T E S T  -----------------------------------------------------------
        $this->assertEquals($total, $this->redis->zCard($this->key));
        // Remove all the keys used
        $this->assertEquals(1, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_sorted_sets_zcard_single()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $this->assertEquals(1, $this->redis->zAdd($this->key, 1.0, 'A'));
        // T E S T  -----------------------------------------------------------
        $this->assertEquals(1, $this->redis->zCard($this->key));
        // Remove all the keys used
        $this->assertEquals(1, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_sorted_sets_zadd_multiple()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        // T E S T  -----------------------------------------------------------
        $this->assertEquals(1, $this->redis->zAdd($this->key, 1.0, 'A'));
        $this->assertEquals(1, $this->redis->zAdd($this->key, 2.0, 'B'));
        // T E S T  -----------------------------------------------------------
        // Remove all the keys used
        $this->assertEquals(1, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_sorted_sets_zadd_duplicate()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        // T E S T  -----------------------------------------------------------
        $this->assertEquals(1, $this->redis->zAdd($this->key, 1.0, 'A'));
        $this->assertEquals(0, $this->redis->zAdd($this->key, 2.0, 'A'));
        // T E S T  -----------------------------------------------------------
        // Remove all the keys used
        $this->assertEquals(1, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_sorted_sets_zadd_single()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        // T E S T  -----------------------------------------------------------
        $this->assertEquals(1, $this->redis->zAdd($this->key, 1.0, 'A'));
        // Remove all the keys used
        $this->assertEquals(1, $this->redis->delete($this->key));
    }
}
