<?php

namespace Webdcg\Redis\Tests;

use PHPUnit\Framework\TestCase;
use Webdcg\Redis\Exceptions\SetOperationException;
use Webdcg\Redis\Exceptions\UnsupportedOptionException;
use Webdcg\Redis\Redis;

class zRangeByScoreTest extends TestCase
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
        $this->key = 'SortedSets:zRangeByScore';
        $this->keyOptional = 'SortedSets:zRangeByScore:Optional';
    }

    /*
     * ========================================================================
     * zRangeByScore
     * ========================================================================
     */


    /** @test */
    public function redis_sorted_sets_zrangebyscore_options()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $total = random_int(5, 10);
        for ($i = 0; $i < $total; $i++) {
            $this->assertEquals(1, $this->redis->zAdd($this->key, 1.1 * $i, chr($i + 65)));
        }
        // T E S T  -----------------------------------------------------------
        $expected = [
            'B' => 1.1,
            'C' => 2.2,
        ];
        $range = $this->redis->zRangeByScore($this->key, 0, 4, ['withscores' => true, 'limit' => [1, 2]]);
        $this->assertIsArray($range);
        $this->assertEquals(2, count($range));
        $this->assertArraySubset(['B' => 1.1], $range);
        $this->assertArraySubset(['C' => 2.2], $range);
        $this->assertArrayNotHasKey('D', $range);
        $this->assertEquals($expected, $range);
        // Remove all the keys used
        $this->assertEquals(1, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_sorted_sets_zrangebyscore_options_limit_multiple()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $total = random_int(5, 10);
        for ($i = 0; $i < $total; $i++) {
            $this->assertEquals(1, $this->redis->zAdd($this->key, 1.1 * $i, chr($i + 65)));
        }
        // T E S T  -----------------------------------------------------------
        $expected = [
            'C', 'D'
        ];
        $range = $this->redis->zRangeByScore($this->key, 0, 4, ['limit' => [2, 2]]);
        $this->assertIsArray($range);
        $this->assertEquals(2, count($range));
        $this->assertContains('C', $range);
        $this->assertContains('D', $range);
        $this->assertArrayNotHasKey('D', $range);
        $this->assertEquals($expected, $range);
        // Remove all the keys used
        $this->assertEquals(1, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_sorted_sets_zrangebyscore_options_limit_single()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $total = random_int(5, 10);
        for ($i = 0; $i < $total; $i++) {
            $this->assertEquals(1, $this->redis->zAdd($this->key, 1.1 * $i, chr($i + 65)));
        }
        // T E S T  -----------------------------------------------------------
        $expected = ['B'];
        $range = $this->redis->zRangeByScore($this->key, 0, 3, ['limit' => [1, 1]]);
        $this->assertIsArray($range);
        $this->assertEquals(1, count($range));
        $this->assertContains('B', $range);
        $this->assertArrayNotHasKey('D', $range);
        $this->assertEquals($expected, $range);
        // Remove all the keys used
        $this->assertEquals(1, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_sorted_sets_zrangebyscore_options_withscores()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $total = random_int(5, 10);
        for ($i = 0; $i < $total; $i++) {
            $this->assertEquals(1, $this->redis->zAdd($this->key, 1.1 * $i, chr($i + 65)));
        }
        // T E S T  -----------------------------------------------------------
        $expected = [
            'A' => 0.0,
            'B' => 1.1,
            'C' => 2.2,
        ];
        $range = $this->redis->zRangeByScore($this->key, 0, 3, ['withscores' => true]);
        $this->assertIsArray($range);
        $this->assertEquals(3, count($range));
        $this->assertArraySubset(['A' => 0.0], $range);
        $this->assertArraySubset(['B' => 1.1], $range);
        $this->assertArraySubset(['C' => 2.2], $range);
        $this->assertArrayNotHasKey('D', $range);
        $this->assertEquals($expected, $range);
        // Remove all the keys used
        $this->assertEquals(1, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_sorted_sets_zrangebyscore_defaults_exclusive_both_ends()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $total = random_int(5, 10);
        for ($i = 0; $i < $total; $i++) {
            $this->assertEquals(1, $this->redis->zAdd($this->key, 1.1 * $i, chr($i + 65)));
        }
        // T E S T  -----------------------------------------------------------
        $expected = [
            'B', 'C'
        ];
        $range = $this->redis->zRangeByScore($this->key, '(0', '(3.3');
        $this->assertIsArray($range);
        $this->assertEquals(2, count($range));
        $this->assertContains('B', $range);
        $this->assertContains('C', $range);
        $this->assertEquals($expected, $range);
        // Remove all the keys used
        $this->assertEquals(1, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_sorted_sets_zrangebyscore_defaults_exclusive_end()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $total = random_int(5, 10);
        for ($i = 0; $i < $total; $i++) {
            $this->assertEquals(1, $this->redis->zAdd($this->key, 1.1 * $i, chr($i + 65)));
        }
        // T E S T  -----------------------------------------------------------
        $expected = [
            'A', 'B', 'C'
        ];
        $range = $this->redis->zRangeByScore($this->key, 0, '(3.3');
        $this->assertIsArray($range);
        $this->assertEquals(3, count($range));
        $this->assertContains('A', $range);
        $this->assertContains('B', $range);
        $this->assertContains('C', $range);
        $this->assertEquals($expected, $range);
        // Remove all the keys used
        $this->assertEquals(1, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_sorted_sets_zrangebyscore_defaults_exclusive_start()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $total = random_int(5, 10);
        for ($i = 0; $i < $total; $i++) {
            $this->assertEquals(1, $this->redis->zAdd($this->key, 1.1 * $i, chr($i + 65)));
        }
        // T E S T  -----------------------------------------------------------
        $expected = ['B', 'C', 'D'];
        $range = $this->redis->zRangeByScore($this->key, '(0', 3.5);
        $this->assertIsArray($range);
        $this->assertEquals(3, count($range));
        $this->assertContains('B', $range);
        $this->assertContains('C', $range);
        $this->assertContains('D', $range);
        $this->assertEquals($expected, $range);
        // Remove all the keys used
        $this->assertEquals(1, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_sorted_sets_zrangebyscore_defaults_middle()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $total = random_int(5, 10);
        for ($i = 0; $i < $total; $i++) {
            $this->assertEquals(1, $this->redis->zAdd($this->key, 1.1 * $i, chr($i + 65)));
        }
        // T E S T  -----------------------------------------------------------
        $expected = ['C', 'D', 'E'];
        $range = $this->redis->zRangeByScore($this->key, 2, 5);
        $this->assertIsArray($range);
        $this->assertEquals(3, count($range));
        $this->assertContains('C', $range);
        $this->assertContains('D', $range);
        $this->assertContains('E', $range);
        $this->assertEquals($expected, $range);
        // Remove all the keys used
        $this->assertEquals(1, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_sorted_sets_zrangebyscore_defaults_out_of_bounds()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $total = random_int(5, 10);
        for ($i = 0; $i < $total; $i++) {
            $this->assertEquals(1, $this->redis->zAdd($this->key, 1.1 * $i, chr($i + 65)));
        }
        // T E S T  -----------------------------------------------------------
        $range = $this->redis->zRangeByScore($this->key, 100, 200);
        $this->assertIsArray($range);
        $this->assertEquals(0, count($range));
        $this->assertNotContains('A', $range);
        // Remove all the keys used
        $this->assertEquals(1, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_sorted_sets_zrangebyscore_defaults_top_infinite()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $total = 10;
        for ($i = 0; $i < $total; $i++) {
            $this->assertEquals(1, $this->redis->zAdd($this->key, 1.1 * $i, chr($i + 65)));
        }
        // T E S T  -----------------------------------------------------------
        $expected = ['F', 'G', 'H', 'I', 'J'];
        $range = $this->redis->zRangeByScore($this->key, 5, '+inf');
        $this->assertIsArray($range);
        $this->assertEquals(5, count($range));
        $this->assertContains('F', $range);
        $this->assertContains('G', $range);
        $this->assertContains('H', $range);
        $this->assertContains('I', $range);
        $this->assertContains('J', $range);
        $this->assertEquals($expected, $range);
        // Remove all the keys used
        $this->assertEquals(1, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_sorted_sets_zrangebyscore_defaults_bottom_infinite()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $total = random_int(5, 10);
        for ($i = 0; $i < $total; $i++) {
            $this->assertEquals(1, $this->redis->zAdd($this->key, 1.1 * $i, chr($i + 65)));
        }
        // T E S T  -----------------------------------------------------------
        $expected = ['A'];
        $range = $this->redis->zRangeByScore($this->key, '-inf', 1);
        $this->assertIsArray($range);
        $this->assertEquals(1, count($range));
        $this->assertContains('A', $range);
        $this->assertEquals($expected, $range);
        // Remove all the keys used
        $this->assertEquals(1, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_sorted_sets_zrangebyscore_defaults_bottom()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $total = random_int(5, 10);
        for ($i = 0; $i < $total; $i++) {
            $this->assertEquals(1, $this->redis->zAdd($this->key, 1.1 * $i, chr($i + 65)));
        }
        // T E S T  -----------------------------------------------------------
        $range = $this->redis->zRangeByScore($this->key, 0, 1);
        $this->assertIsArray($range);
        $this->assertEquals(1, count($range));
        $this->assertContains('A', $range);
        // Remove all the keys used
        $this->assertEquals(1, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_sorted_sets_zrangebyscore_wrong_operation()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        // T E S T  -----------------------------------------------------------
        $this->expectException(UnsupportedOptionException::class);
        $this->redis->zRangeByScore($this->key, 0, 1, ['wrong' => false]);
    }
}
