<?php

namespace Webdcg\Redis\Tests;

use PHPUnit\Framework\TestCase;
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
    public function redis_sorted_sets_zAdd()
    {
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $this->assertEquals(1, $this->redis->zAdd($this->key, 1.0, 'A'));
        $this->assertEquals(1, $this->redis->zAdd($this->key, 2.0, 'B'));
        $this->assertEquals(1, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_sorted_sets_zCount()
    {
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $total = random_int(2, 10);
        for ($i = 0; $i < $total; $i++) {
            $this->assertEquals(1, $this->redis->zAdd($this->key, 1.0 * ($i + 1), chr($i + 65)));
        }
        $this->assertEquals(2, $this->redis->zCount($this->key, 1, 2));
        $this->assertEquals($total, $this->redis->zCount($this->key, 1, $total));
        $this->assertEquals(2, $this->redis->zCount($this->key, '-inf', 2));
        $this->assertEquals($total, $this->redis->zCount($this->key, 1, '+inf'));
        $this->assertEquals($total, $this->redis->zCount($this->key, '-inf', '+inf'));
        $this->assertEquals(1, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_sorted_sets_zSize()
    {
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $total = random_int(1, 10);
        for ($i = 0; $i < $total; $i++) {
            $this->assertEquals(1, $this->redis->zAdd($this->key, 1.1 * $i, chr($i + 65)));
        }
        $this->assertEquals($total, $this->redis->zSize($this->key));
        $this->assertEquals(1, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_sorted_sets_zCard()
    {
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $total = random_int(1, 10);
        for ($i = 0; $i < $total; $i++) {
            $this->assertEquals(1, $this->redis->zAdd($this->key, 1.1 * $i, chr($i + 65)));
        }
        $this->assertEquals($total, $this->redis->zCard($this->key));
        $this->assertEquals(1, $this->redis->delete($this->key));
    }


    /** @test */
    public function redis_sorted_sets_zIncrBy()
    {
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $this->assertEquals(1, $this->redis->zAdd($this->key, 1.0, 'A'));
        $this->assertEquals(2.0, $this->redis->zIncrBy($this->key, 1.0, 'A'));
        $this->assertEquals(3.5, $this->redis->zIncrBy($this->key, 1.5, 'A'));
        $this->assertEquals(1, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_sorted_sets_zInterStore()
    {
        $destinationKey = $this->key . ':' . $this->keyOptional;
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->keyOptional));
        $this->assertEquals(1, $this->redis->zAdd($this->key, 1.0, 'A'));
        $this->assertEquals(1, $this->redis->zAdd($this->key, 2.0, 'B'));
        $this->assertEquals(2, $this->redis->zCard($this->key));
        $this->assertEquals(1, $this->redis->zAdd($this->keyOptional, 2.0, 'B'));
        $this->assertEquals(1, $this->redis->zAdd($this->keyOptional, 3.0, 'C'));
        $this->assertEquals(2, $this->redis->zCard($this->keyOptional));
        $this->assertEquals(1, $this->redis->zInterStore($destinationKey, [$this->key, $this->keyOptional], [1, 1], 'SUM'));
        $this->assertEquals(1, $this->redis->zCard($destinationKey));
        $this->assertEquals(1, $this->redis->delete($this->key));
        $this->assertEquals(1, $this->redis->delete($this->keyOptional));
        $this->assertEquals(1, $this->redis->delete($destinationKey));
    }

    /** @test */
    public function redis_sorted_sets_zInter()
    {
        $destinationKey = $this->key . ':' . $this->keyOptional;
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->keyOptional));
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($destinationKey));
        $this->assertEquals(1, $this->redis->zAdd($this->key, 1.0, 'A'));
        $this->assertEquals(1, $this->redis->zAdd($this->key, 2.5, 'B'));
        $this->assertEquals(2, $this->redis->zCard($this->key));
        $this->assertEquals(1, $this->redis->zAdd($this->keyOptional, 2.0, 'B'));
        $this->assertEquals(1, $this->redis->zAdd($this->keyOptional, 3.0, 'C'));
        $this->assertEquals(2, $this->redis->zCard($this->keyOptional));
        $this->assertEquals(1, $this->redis->zInter($destinationKey, [$this->key, $this->keyOptional], [1, 1], 'MAX'));
        $this->assertEquals(1, $this->redis->zCard($destinationKey));
        $this->assertEquals(1, $this->redis->delete($this->key));
        $this->assertEquals(1, $this->redis->delete($this->keyOptional));
        $this->assertEquals(1, $this->redis->delete($destinationKey));
    }

    /** @test */
    public function redis_sorted_sets_zPopMax()
    {
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $this->assertEquals(1, $this->redis->zAdd($this->key, 1.1, 'A'));
        $this->assertEquals(1, $this->redis->zAdd($this->key, 2.2, 'B'));
        $this->assertEquals(1, $this->redis->zAdd($this->key, 3.3, 'C'));
        $this->assertEquals(3, $this->redis->zCard($this->key));
        $max = $this->redis->zPopMax($this->key, 2);
        $this->assertIsArray($max);
        $this->assertArraySubset(['B' => 2.2], $max);
        $this->assertArraySubset(['C' => 3.3], $max);
        $this->assertEquals(1, $this->redis->zCard($this->key));
        $this->assertEquals(1, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_sorted_sets_zPopMin()
    {
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $this->assertEquals(1, $this->redis->zAdd($this->key, 1.1, 'A'));
        $this->assertEquals(1, $this->redis->zAdd($this->key, 2.2, 'B'));
        $this->assertEquals(1, $this->redis->zAdd($this->key, 3.3, 'C'));
        $this->assertEquals(3, $this->redis->zCard($this->key));
        $min = $this->redis->zPopMin($this->key, 2);
        $this->assertIsArray($min);
        $this->assertArraySubset(['A' => 1.1], $min);
        $this->assertArraySubset(['B' => 2.2], $min);
        $this->assertEquals(1, $this->redis->zCard($this->key));
        $this->assertEquals(1, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_sorted_sets_zRangeByScore()
    {
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $total = random_int(5, 10);
        for ($i = 0; $i < $total; $i++) {
            $this->assertEquals(1, $this->redis->zAdd($this->key, 1.1 * $i, chr($i + 65)));
        }
        $expected = ['B' => 1.1, 'C' => 2.2, ];
        $range = $this->redis->zRangeByScore($this->key, 0, 4, ['withscores' => true, 'limit' => [1, 2]]);
        $this->assertIsArray($range);
        $this->assertEquals(2, count($range));
        $this->assertArraySubset(['B' => 1.1], $range);
        $this->assertArraySubset(['C' => 2.2], $range);
        $this->assertArrayNotHasKey('D', $range);
        $this->assertEquals($expected, $range);
        $this->assertEquals(1, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_sorted_sets_zRange()
    {
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $this->assertEquals(1, $this->redis->zAdd($this->key, 1.1, 'A'));
        $this->assertEquals(1, $this->redis->zAdd($this->key, 2.2, 'B'));
        $this->assertEquals(1, $this->redis->zAdd($this->key, 3.3, 'C'));
        $this->assertEquals(3, $this->redis->zCard($this->key));
        $range = $this->redis->zRange($this->key);
        $this->assertIsArray($range);
        $this->assertEquals(3, count($range));
        $this->assertContains('A', $range);
        $this->assertContains('B', $range);
        $this->assertContains('C', $range);
        $this->assertNotContains('D', $range);
        $this->assertEquals(1, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_sorted_sets_zRevRangeByScore()
    {
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $total = 10;
        for ($i = 0; $i < $total; $i++) {
            $this->assertEquals(1, $this->redis->zAdd($this->key, 1.1 * $i, chr($i + 65)));
        }
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
        $this->assertEquals(1, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_sorted_sets_zRangeByLex()
    {
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $total = 10;
        $data = [];
        for ($i = 0; $i < $total; $i++) {
            $member = chr($i + 65);
            $value = 1.1;
            $data[$member] = $value;
            $this->assertEquals(1, $this->redis->zAdd($this->key, $value, $member));
        }
        $expected = array_keys($data);
        $range = $this->redis->zRangeByLex($this->key, '-', '[E', 2, 2);
        $this->assertIsArray($range);
        $this->assertEquals(2, count($range));
        for ($i = 2; $i < 4; $i++) {
            $this->assertContains($expected[$i], $range);
        }
        $this->assertEquals(array_slice($expected, 2, 2), $range);
        $this->assertEquals(1, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_sorted_sets_zRank()
    {
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $total = random_int(1, 10);
        $data = [];
        for ($i = 0; $i < $total; $i++) {
            $member = chr($i + 65);
            $value = 1 * $i;
            $data[$member] = $value;
            $this->assertEquals(1, $this->redis->zAdd($this->key, $value, $member));
        }
        $member = random_int(1, $total);
        $member = array_keys($data)[$member - 1];
        $rank = $this->redis->zRank($this->key, $member);
        $this->assertIsInt($rank);
        $this->assertEquals($data[$member], $rank);
        $this->assertEquals(1, $this->redis->delete($this->key));
    }
}
