<?php

namespace Webdcg\Redis\Tests;

use PHPUnit\Framework\TestCase;
use Webdcg\Redis\Redis;

class RedisGeocodingTest extends TestCase
{
    protected $redis;

    public function setUp()
    {
        $this->redis = new Redis;
        $this->redis->connect();
    }

    /** @test */
    public function redis_geocoding_geoadd_single()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete('Geocoding'));
        // Add San Francisco to the geospatial key should only do it once
        $this->assertEquals(1, $this->redis->geoAdd('Geocoding', -122.431, 37.773, 'San Francisco'));
        $this->assertEquals(0, $this->redis->geoAdd('Geocoding', -122.431, 37.773, 'San Francisco'));
        // Cleanup used keys
        $this->assertGreaterThanOrEqual(0, $this->redis->delete('Geocoding'));
    }
}
