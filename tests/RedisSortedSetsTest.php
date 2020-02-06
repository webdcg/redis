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
    public function redis_sorted_sets_zadd()
    {
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $this->assertEquals(1, $this->redis->zAdd($this->key, 1.0, 'A'));
        $this->assertEquals(1, $this->redis->zAdd($this->key, 2.0, 'B'));
        $this->assertEquals(1, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_sorted_sets_zcount()
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
    public function redis_sorted_sets_zsize()
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
    public function redis_sorted_sets_zcard()
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
    public function redis_sorted_sets_zincrby()
    {
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $this->assertEquals(1, $this->redis->zAdd($this->key, 1.0, 'A'));
        $this->assertEquals(2.0, $this->redis->zIncrBy($this->key, 1.0, 'A'));
        $this->assertEquals(3.5, $this->redis->zIncrBy($this->key, 1.5, 'A'));
        $this->assertEquals(1, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_sorted_sets_zinterstore()
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
    public function redis_sorted_sets_zinter()
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
    public function redis_sorted_sets_zpopmax_multiple()
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
    public function redis_sorted_sets_zpopmin()
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
    public function redis_sorted_sets_zrangebyscore()
    {
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $total = random_int(5, 10);
        for ($i = 0; $i < $total; $i++) {
            $this->assertEquals(1, $this->redis->zAdd($this->key, 1.1 * $i, chr($i + 65)));
        }
        $expected = ['B' => 1.1, 'C' => 2.2,];
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
    public function redis_sorted_sets_zrange()
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
}
