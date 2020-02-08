<?php

namespace Webdcg\Redis\Tests;

use PHPUnit\Framework\TestCase;
use Webdcg\Redis\Exceptions\UnsupportedOptionException;
use Webdcg\Redis\Redis;

class zRevRangeByScoreTest extends TestCase
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
        $this->key = 'SortedSets:zRevRangeByScore';
        $this->keyOptional = 'SortedSets:zRevRangeByScore:Optional';
    }

    /*
     * ========================================================================
     * zRevRangeByScore
     * ========================================================================
     */

    /** @test */
    public function redis_sorted_sets_zRevRangeByScore_options()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $total = 10;
        for ($i = 0; $i < $total; $i++) {
            $this->assertEquals(1, $this->redis->zAdd($this->key, 1.1 * $i, chr($i + 65)));
        }
        // T E S T  -----------------------------------------------------------
        $expected = [
            'F' => 5.5,
            'E' => 4.4,
        ];
        $range = $this->redis->zRevRangeByScore($this->key, 7, 3, ['withscores' => true, 'limit' => [1, 2]]);
        $this->assertIsArray($range);
        $this->assertEquals(2, count($range));
        $this->assertArraySubset(['F' => 5.5], $range);
        $this->assertArraySubset(['E' => 4.4], $range);
        $this->assertArrayNotHasKey('D', $range);
        $this->assertEquals($expected, $range);
        // Remove all the keys used
        $this->assertEquals(1, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_sorted_sets_zRevRangeByScore_options_limit_multiple()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $total = 10;
        for ($i = 0; $i < $total; $i++) {
            $this->assertEquals(1, $this->redis->zAdd($this->key, 1.1 * $i, chr($i + 65)));
        }
        // T E S T  -----------------------------------------------------------
        $expected = ['F', 'E'];
        $range = $this->redis->zRevRangeByScore($this->key, 8, 4, ['limit' => [2, 2]]);
        $this->assertIsArray($range);
        $this->assertEquals(2, count($range));
        $this->assertContains('F', $range);
        $this->assertContains('E', $range);
        $this->assertArrayNotHasKey('D', $range);
        $this->assertEquals($expected, $range);
        // Remove all the keys used
        $this->assertEquals(1, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_sorted_sets_zRevRangeByScore_options_limit_single()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $total = 10;
        for ($i = 0; $i < $total; $i++) {
            $this->assertEquals(1, $this->redis->zAdd($this->key, 1.1 * $i, chr($i + 65)));
        }
        // T E S T  -----------------------------------------------------------
        $expected = ['H'];
        $range = $this->redis->zRevRangeByScore($this->key, 9, 5, ['limit' => [1, 1]]);
        $this->assertIsArray($range);
        $this->assertEquals(1, count($range));
        $this->assertContains('H', $range);
        $this->assertArrayNotHasKey('D', $range);
        $this->assertEquals($expected, $range);
        // Remove all the keys used
        $this->assertEquals(1, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_sorted_sets_zRevRangeByScore_options_withscores()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $total = 10;
        for ($i = 0; $i < $total; $i++) {
            $this->assertEquals(1, $this->redis->zAdd($this->key, 1.1 * $i, chr($i + 65)));
        }
        // T E S T  -----------------------------------------------------------
        $expected = [
            'J' => 9.9,
            'I' => 8.8,
            'H' => 7.7,
            'G' => 6.6,
            'F' => 5.5,
        ];
        $range = $this->redis->zRevRangeByScore($this->key, 10, 5, ['withscores' => true]);
        $this->assertIsArray($range);
        $this->assertEquals(5, count($range));
        // ToDo Loopp through the $expected array
        $this->assertArraySubset(['J' => 9.9], $range);
        $this->assertArraySubset(['I' => 8.8], $range);
        // $this->assertArraySubset(['H' => 7.7], $range);
        // $this->assertArraySubset(['G' => 6.6], $range);
        $this->assertArraySubset(['F' => 5.5], $range);
        $this->assertArrayNotHasKey('D', $range);
        $this->assertEquals($expected, $range);
        // Remove all the keys used
        $this->assertEquals(1, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_sorted_sets_zRevRangeByScore_defaults_exclusive_both_ends()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $total = 10;
        for ($i = 0; $i < $total; $i++) {
            $this->assertEquals(1, $this->redis->zAdd($this->key, 1.1 * $i, chr($i + 65)));
        }
        // T E S T  -----------------------------------------------------------
        $expected = ['I', 'H', 'G', 'F'];
        $range = $this->redis->zRevRangeByScore($this->key, '(9', '(4.4');
        $this->assertIsArray($range);
        $this->assertEquals(4, count($range));
        $this->assertContains('I', $range);
        $this->assertContains('H', $range);
        $this->assertContains('G', $range);
        $this->assertContains('F', $range);
        $this->assertEquals($expected, $range);
        // Remove all the keys used
        $this->assertEquals(1, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_sorted_sets_zRevRangeByScore_defaults_exclusive_end()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $total = 10;
        for ($i = 0; $i < $total; $i++) {
            $this->assertEquals(1, $this->redis->zAdd($this->key, 1.1 * $i, chr($i + 65)));
        }
        // T E S T  -----------------------------------------------------------
        $expected = ['I', 'H', 'G', 'F'];
        $range = $this->redis->zRevRangeByScore($this->key, 9, '(4.4');
        $this->assertIsArray($range);
        $this->assertEquals(4, count($range));
        $this->assertContains('I', $range);
        $this->assertContains('H', $range);
        $this->assertContains('G', $range);
        $this->assertContains('F', $range);
        $this->assertEquals($expected, $range);
        // Remove all the keys used
        $this->assertEquals(1, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_sorted_sets_zRevRangeByScore_defaults_exclusive_start()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $total = 10;
        for ($i = 0; $i < $total; $i++) {
            $this->assertEquals(1, $this->redis->zAdd($this->key, 1.1 * $i, chr($i + 65)));
        }
        // T E S T  -----------------------------------------------------------
        $expected = ['I', 'H', 'G', 'F'];
        $range = $this->redis->zRevRangeByScore($this->key, '(9', 5);
        $this->assertIsArray($range);
        $this->assertEquals(4, count($range));
        $this->assertContains('I', $range);
        $this->assertContains('H', $range);
        $this->assertContains('G', $range);
        $this->assertContains('F', $range);
        $this->assertEquals($expected, $range);
        // Remove all the keys used
        $this->assertEquals(1, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_sorted_sets_zRevRangeByScore_defaults_middle()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $total = 10;
        for ($i = 0; $i < $total; $i++) {
            $this->assertEquals(1, $this->redis->zAdd($this->key, 1.1 * $i, chr($i + 65)));
        }
        // T E S T  -----------------------------------------------------------
        $expected = ['G', 'F', 'E'];
        $range = $this->redis->zRevRangeByScore($this->key, 7, 4);
        $this->assertIsArray($range);
        $this->assertEquals(3, count($range));
        $this->assertContains('G', $range);
        $this->assertContains('F', $range);
        $this->assertContains('E', $range);
        $this->assertEquals($expected, $range);
        // Remove all the keys used
        $this->assertEquals(1, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_sorted_sets_zRevRangeByScore_defaults_out_of_bounds()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $total = random_int(5, 10);
        for ($i = 0; $i < $total; $i++) {
            $this->assertEquals(1, $this->redis->zAdd($this->key, 1.1 * $i, chr($i + 65)));
        }
        // T E S T  -----------------------------------------------------------
        $range = $this->redis->zRevRangeByScore($this->key, 100, 200);
        $this->assertIsArray($range);
        $this->assertEquals(0, count($range));
        $this->assertNotContains('A', $range);
        // Remove all the keys used
        $this->assertEquals(1, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_sorted_sets_zRevRangeByScore_defaults_bottom_infinite()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $total = 10;
        for ($i = 0; $i < $total; $i++) {
            $this->assertEquals(1, $this->redis->zAdd($this->key, 1.1 * $i, chr($i + 65)));
        }
        // T E S T  -----------------------------------------------------------
        $expected = ['E', 'D', 'C', 'B', 'A'];
        $range = $this->redis->zRevRangeByScore($this->key, 5, '-inf');
        $this->assertIsArray($range);
        $this->assertEquals(5, count($range));
        $this->assertContains('E', $range);
        $this->assertContains('D', $range);
        $this->assertContains('C', $range);
        $this->assertContains('B', $range);
        $this->assertContains('A', $range);
        $this->assertEquals($expected, $range);
        // Remove all the keys used
        $this->assertEquals(1, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_sorted_sets_zRevRangeByScore_defaults_top_infinite()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $total = 10;
        for ($i = 0; $i < $total; $i++) {
            $this->assertEquals(1, $this->redis->zAdd($this->key, 1.1 * $i, chr($i + 65)));
        }
        // T E S T  -----------------------------------------------------------
        $expected = ['J', 'I', 'H', 'G', 'F'];
        $range = $this->redis->zRevRangeByScore($this->key, '+inf', 5);
        $this->assertIsArray($range);
        $this->assertEquals(5, count($range));
        $this->assertContains('J', $range);
        $this->assertContains('I', $range);
        $this->assertContains('H', $range);
        $this->assertContains('G', $range);
        $this->assertContains('F', $range);
        $this->assertEquals($expected, $range);
        // Remove all the keys used
        $this->assertEquals(1, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_sorted_sets_zRevRangeByScore_defaults_bottom()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $total = 10;
        for ($i = 0; $i < $total; $i++) {
            $this->assertEquals(1, $this->redis->zAdd($this->key, 1.1 * $i, chr($i + 65)));
        }
        // T E S T  -----------------------------------------------------------
        $range = $this->redis->zRevRangeByScore($this->key, 10, 9);
        $this->assertIsArray($range);
        $this->assertEquals(1, count($range));
        $this->assertContains('J', $range);
        // Remove all the keys used
        $this->assertEquals(1, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_sorted_sets_zRevRangeByScore_wrong_operation()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        // T E S T  -----------------------------------------------------------
        $this->expectException(UnsupportedOptionException::class);
        $this->redis->zRevRangeByScore($this->key, 0, 1, ['wrong' => false]);
    }
}
