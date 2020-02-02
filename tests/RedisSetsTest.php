<?php

namespace Webdcg\Redis\Tests;

use PHPUnit\Framework\TestCase;
use Webdcg\Redis\Redis;

class RedisSetsTest extends TestCase
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
        $this->key = 'Sets';
        $this->keyOptional = 'Sets:Optional';
    }

    /** @test */
    public function redis_sets_srandmember_random_elements()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $this->assertEquals(1, $this->redis->sAdd($this->key, 'A'));
        $this->assertEquals(1, $this->redis->sAdd($this->key, 'B'));
        $this->assertEquals(1, $this->redis->sAdd($this->key, 'C'));
        // --------------------  T E S T  --------------------
        $popped = $this->redis->sRandMember($this->key, 2);
        $this->assertContains($popped[0], ['A', 'B', 'C']);
        $this->assertContains($popped[1], ['A', 'B', 'C']);
        $this->assertEquals(3, $this->redis->sCard($this->key));
        // Cleanup
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_sets_srandmember_random_element()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $this->assertEquals(1, $this->redis->sAdd($this->key, 'A'));
        $this->assertEquals(1, $this->redis->sAdd($this->key, 'B'));
        $this->assertEquals(1, $this->redis->sAdd($this->key, 'C'));
        // --------------------  T E S T  --------------------
        $this->assertContains($this->redis->sRandMember($this->key), ['A', 'B', 'C']);
        $this->assertEquals(3, $this->redis->sCard($this->key));
        // Cleanup
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_sets_srandmember_non_existing_keys()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $this->assertTrue($this->redis->set($this->key, 'value'));
        // --------------------  T E S T  --------------------
        $this->assertFalse($this->redis->sRandMember($this->key));
        // Cleanup
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_sets_spop_random_elements()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $this->assertEquals(1, $this->redis->sAdd($this->key, 'A'));
        $this->assertEquals(1, $this->redis->sAdd($this->key, 'B'));
        $this->assertEquals(1, $this->redis->sAdd($this->key, 'C'));
        // --------------------  T E S T  --------------------
        $popped = $this->redis->sPop($this->key, 2);
        $this->assertContains($popped[0], ['A', 'B', 'C']);
        $this->assertContains($popped[1], ['A', 'B', 'C']);
        $this->assertEquals(1, $this->redis->sCard($this->key));
        // Cleanup
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_sets_spop_random_element()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $this->assertEquals(1, $this->redis->sAdd($this->key, 'A'));
        $this->assertEquals(1, $this->redis->sAdd($this->key, 'B'));
        $this->assertEquals(1, $this->redis->sAdd($this->key, 'C'));
        // --------------------  T E S T  --------------------
        $this->assertContains($this->redis->sPop($this->key), ['A', 'B', 'C']);
        $this->assertEquals(2, $this->redis->sCard($this->key));
        // Cleanup
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_sets_spop_non_existing_keys()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $this->assertTrue($this->redis->set($this->key, 'value'));
        // --------------------  T E S T  --------------------
        $this->assertFalse($this->redis->sPop($this->key));
        // Cleanup
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_sets_smove_existing_keys_missing_member()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->keyOptional));

        $this->assertEquals(1, $this->redis->sAdd($this->key, 'C'));
        $this->assertEquals(1, $this->redis->sAdd($this->keyOptional, 'B'));

        // --------------------  T E S T  --------------------
        $this->assertFalse($this->redis->sMove($this->key, $this->keyOptional, 'A'));

        // Cleanup
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $this->assertEquals(1, $this->redis->delete($this->keyOptional));
    }

    /** @test */
    public function redis_sets_smove_non_existing_keys()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->keyOptional));

        // --------------------  T E S T  --------------------
        $this->assertFalse($this->redis->sMove($this->key, $this->keyOptional, 'A'));
    }

    /** @test */
    public function redis_sets_smove_existing_keys()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->keyOptional));

        $this->assertEquals(1, $this->redis->sAdd($this->key, 'A'));
        $this->assertEquals(1, $this->redis->sAdd($this->keyOptional, 'B'));

        // --------------------  T E S T  --------------------
        $this->assertTrue($this->redis->sMove($this->key, $this->keyOptional, 'A'));
        $this->assertEquals(2, $this->redis->sCard($this->keyOptional));

        // Cleanup
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $this->assertEquals(1, $this->redis->delete($this->keyOptional));
    }

    /** @test */
    public function redis_sets_sGetMembers_does_not_find_elements()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $this->assertEquals(1, $this->redis->sAdd($this->key, 'A'));
        $this->assertEquals(1, $this->redis->sAdd($this->key, 'B'));
        // --------------------  T E S T  --------------------
        $this->assertIsArray($this->redis->sGetMembers($this->key));
        $this->assertContains('A', $this->redis->sGetMembers($this->key));
        $this->assertNotContains('C', $this->redis->sGetMembers($this->key));
        $this->assertArraySubset(['A', 'B'], $this->redis->sGetMembers($this->key));
        // Cleanup
        $this->assertEquals(1, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_sets_sGetMembers_finds_elements()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $this->assertEquals(1, $this->redis->sAdd($this->key, 'A'));
        $this->assertEquals(1, $this->redis->sAdd($this->key, 'B'));
        // --------------------  T E S T  --------------------
        $this->assertIsArray($this->redis->sGetMembers($this->key));
        $this->assertContains('A', $this->redis->sGetMembers($this->key));
        $this->assertContains('B', $this->redis->sGetMembers($this->key));
        $this->assertArraySubset(['A', 'B'], $this->redis->sGetMembers($this->key));
        // Cleanup
        $this->assertEquals(1, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_sets_smembers_does_not_find_elements()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $this->assertEquals(1, $this->redis->sAdd($this->key, 'A'));
        $this->assertEquals(1, $this->redis->sAdd($this->key, 'B'));
        // --------------------  T E S T  --------------------
        $this->assertIsArray($this->redis->sMembers($this->key));
        $this->assertContains('A', $this->redis->sMembers($this->key));
        $this->assertNotContains('C', $this->redis->sMembers($this->key));
        $this->assertArraySubset(['A', 'B'], $this->redis->sMembers($this->key));
        // Cleanup
        $this->assertEquals(1, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_sets_smembers_finds_elements()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $this->assertEquals(1, $this->redis->sAdd($this->key, 'A'));
        $this->assertEquals(1, $this->redis->sAdd($this->key, 'B'));
        // --------------------  T E S T  --------------------
        $this->assertIsArray($this->redis->sMembers($this->key));
        $this->assertContains('A', $this->redis->sMembers($this->key));
        $this->assertContains('B', $this->redis->sMembers($this->key));
        $this->assertArraySubset(['A', 'B'], $this->redis->sMembers($this->key));
        // Cleanup
        $this->assertEquals(1, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_sets_contains_finds_elements()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $this->assertEquals(1, $this->redis->sAdd($this->key, 'A'));
        // --------------------  T E S T  --------------------
        $this->assertEquals(true, $this->redis->sContains($this->key, 'A'));
        $this->assertTrue($this->redis->sContains($this->key, 'A'));
        // Cleanup
        $this->assertEquals(1, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_sets_contains_does_not_find_the_element()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $this->assertEquals(1, $this->redis->sAdd($this->key, 'A'));
        // --------------------  T E S T  --------------------
        $this->assertEquals(false, $this->redis->sContains($this->key, 'B'));
        $this->assertFalse($this->redis->sContains($this->key, 'B'));
        // Cleanup
        $this->assertEquals(1, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_sets_ismember_finds_elements()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $this->assertEquals(1, $this->redis->sAdd($this->key, 'A'));
        // --------------------  T E S T  --------------------
        $this->assertEquals(true, $this->redis->sIsMember($this->key, 'A'));
        $this->assertTrue($this->redis->sIsMember($this->key, 'A'));
        // Cleanup
        $this->assertEquals(1, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_sets_ismember_does_not_find_the_element()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $this->assertEquals(1, $this->redis->sAdd($this->key, 'A'));
        // --------------------  T E S T  --------------------
        $this->assertEquals(false, $this->redis->sIsMember($this->key, 'B'));
        $this->assertFalse($this->redis->sIsMember($this->key, 'B'));
        // Cleanup
        $this->assertEquals(1, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_sets_sinterstore_simple()
    {
        $destinationKey = $this->key . ':' . $this->keyOptional;
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->keyOptional));
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($destinationKey));

        $this->assertEquals(1, $this->redis->sAdd($this->key, 'A'));
        $this->assertEquals(1, $this->redis->sAdd($this->key, 'B'));
        $this->assertEquals(1, $this->redis->sAdd($this->key, 'C'));
        $this->assertEquals(1, $this->redis->sAdd($this->key, 'D'));

        $this->assertEquals(1, $this->redis->sAdd($this->keyOptional, 'B'));
        $this->assertEquals(1, $this->redis->sAdd($this->keyOptional, 'C'));

        // --------------------  T E S T  --------------------
        $this->assertEquals(2, $this->redis->sInterStore($destinationKey, $this->key, $this->keyOptional));
        // --------------------  T E S T  --------------------

        $this->assertEquals(2, $this->redis->sSize($destinationKey));
        $this->assertEquals(0, $this->redis->sAdd($destinationKey, 'B'));

        // Cleanup
        $this->assertEquals(1, $this->redis->delete($this->key));
        $this->assertEquals(1, $this->redis->delete($this->keyOptional));
        $this->assertEquals(1, $this->redis->delete($destinationKey));
    }

    /** @test */
    public function redis_sets_sint_simple()
    {
        $destinationKey = $this->key . ':' . $this->keyOptional;
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->keyOptional));
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($destinationKey));

        $this->assertEquals(1, $this->redis->sAdd($this->key, 'A'));
        $this->assertEquals(1, $this->redis->sAdd($this->key, 'B'));
        $this->assertEquals(1, $this->redis->sAdd($this->key, 'C'));
        $this->assertEquals(1, $this->redis->sAdd($this->key, 'D'));

        $this->assertEquals(1, $this->redis->sAdd($this->keyOptional, 'B'));
        $this->assertEquals(1, $this->redis->sAdd($this->keyOptional, 'C'));
        // --------------------  T E S T  --------------------
        $this->assertContains('B', $this->redis->sInter($this->key, $this->keyOptional));
        $this->assertContains('C', $this->redis->sInter($this->key, $this->keyOptional));
        // --------------------  T E S T  --------------------

        $this->assertEquals(1, $this->redis->sAdd($destinationKey, 'B'));
        $this->assertEquals(1, $this->redis->sAdd($destinationKey, 'D'));

        $this->assertContains('B', $this->redis->sInter($this->key, $this->keyOptional, $destinationKey));

        // Cleanup
        $this->assertEquals(1, $this->redis->delete($this->key));
        $this->assertEquals(1, $this->redis->delete($this->keyOptional));
        $this->assertEquals(1, $this->redis->delete($destinationKey));
    }

    /** @test */
    public function redis_sets_sdiffstore_simple()
    {
        $destinationKey = $this->key . ':' . $this->keyOptional;
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->keyOptional));
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($destinationKey));

        $this->assertEquals(1, $this->redis->sAdd($this->key, 'A'));
        $this->assertEquals(1, $this->redis->sAdd($this->key, 'B'));
        $this->assertEquals(1, $this->redis->sAdd($this->keyOptional, 'B'));
        // --------------------  T E S T  --------------------
        $this->assertEquals(1, $this->redis->sDiffStore($destinationKey, $this->key, $this->keyOptional));
        // --------------------  T E S T  --------------------
        $this->assertEquals(1, $this->redis->sCard($destinationKey));

        // Cleanup
        $this->assertEquals(1, $this->redis->delete($this->key));
        $this->assertEquals(1, $this->redis->delete($this->keyOptional));
        $this->assertEquals(1, $this->redis->delete($destinationKey));
    }

    /** @test */
    public function redis_sets_sdiff_simple()
    {
        $destinationKey = $this->key . ':' . $this->keyOptional;
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->keyOptional));
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($destinationKey));

        $this->assertEquals(1, $this->redis->sAdd($this->key, 'A'));
        $this->assertEquals(1, $this->redis->sAdd($this->key, 'B'));
        $this->assertEquals(1, $this->redis->sAdd($this->keyOptional, 'B'));
        // --------------------  T E S T  --------------------
        $this->assertEquals(['A'], $this->redis->sDiff($this->key, $this->keyOptional));
        // --------------------  T E S T  --------------------

        $this->assertEquals(1, $this->redis->sAdd($this->key, 'Z'));
        $this->assertEquals(1, $this->redis->sAdd($this->keyOptional, 'C'));
        $this->assertEquals(1, $this->redis->sAdd($this->key . ':' . $this->keyOptional, 'D'));
        $this->assertEquals(1, $this->redis->sAdd($this->key . ':' . $this->keyOptional, 'E'));
        // --------------------  T E S T  --------------------
        $this->assertEquals(['A', 'Z'], $this->redis->sDiff($this->key, $this->keyOptional, $destinationKey));
        // --------------------  T E S T  --------------------
        // Cleanup
        $this->assertEquals(1, $this->redis->delete($this->key));
        $this->assertEquals(1, $this->redis->delete($this->keyOptional));
        $this->assertEquals(1, $this->redis->delete($destinationKey));
    }

    /** @test */
    public function redis_sets_ssize_multiple()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $this->assertEquals(3, $this->redis->sAdd($this->key, 'A', 'B', 'C'));
        // --------------------  T E S T  --------------------
        $this->assertEquals(3, $this->redis->sSize($this->key));
        // Cleanup
        $this->assertEquals(1, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_sets_ssize_duplicate()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $this->assertEquals(1, $this->redis->sAdd($this->key, 'A'));
        $this->assertEquals(0, $this->redis->sAdd($this->key, 'A'));
        // --------------------  T E S T  --------------------
        $this->assertEquals(1, $this->redis->sSize($this->key));
        // Cleanup
        $this->assertEquals(1, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_sets_ssize_single()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $this->assertEquals(1, $this->redis->sAdd($this->key, 'A'));
        // --------------------  T E S T  --------------------
        $this->assertEquals(1, $this->redis->sSize($this->key));
        // Cleanup
        $this->assertEquals(1, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_sets_scard_multiple()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $this->assertEquals(3, $this->redis->sAdd($this->key, 'A', 'B', 'C'));
        // --------------------  T E S T  --------------------
        $this->assertEquals(3, $this->redis->sCard($this->key));
        // Cleanup
        $this->assertEquals(1, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_sets_scard_duplicate()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $this->assertEquals(1, $this->redis->sAdd($this->key, 'A'));
        $this->assertEquals(0, $this->redis->sAdd($this->key, 'A'));
        // --------------------  T E S T  --------------------
        $this->assertEquals(1, $this->redis->sCard($this->key));
        // Cleanup
        $this->assertEquals(1, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_sets_scard_single()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        $this->assertEquals(1, $this->redis->sAdd($this->key, 'A'));
        // --------------------  T E S T  --------------------
        $this->assertEquals(1, $this->redis->sCard($this->key));
        // Cleanup
        $this->assertEquals(1, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_sets_sadd_array()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        // --------------------  T E S T  --------------------
        $this->assertEquals(3, $this->redis->sAdd($this->key, ['A', 'B', 'C']));
        // Cleanup
        $this->assertEquals(1, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_sets_sadd_multiple()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        // --------------------  T E S T  --------------------
        $this->assertEquals(3, $this->redis->sAdd($this->key, 'A', 'B', 'C'));
        // Cleanup
        $this->assertEquals(1, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_sets_sadd_duplicate()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        // --------------------  T E S T  --------------------
        $this->assertEquals(1, $this->redis->sAdd($this->key, 'A'));
        $this->assertEquals(0, $this->redis->sAdd($this->key, 'A'));
        // Cleanup
        $this->assertEquals(1, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_sets_sadd_single()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        // --------------------  T E S T  --------------------
        $this->assertEquals(1, $this->redis->sAdd($this->key, 'A'));
        // Cleanup
        $this->assertEquals(1, $this->redis->delete($this->key));
    }
}
