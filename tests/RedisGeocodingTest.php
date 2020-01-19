<?php

namespace Webdcg\Redis\Tests;

use PHPUnit\Framework\TestCase;
use Webdcg\Redis\Redis;

class RedisGeocodingTest extends TestCase
{
    protected $redis;
    protected $longitude;
    protected $latitude;
    protected $location;
    protected $key;

    public function setUp()
    {
        $this->redis = new Redis;
        $this->redis->connect();

        $this->longitude = -122.431;
        $this->latitude = 37.773;
        $this->location = 'San Francisco';
        $this->key = 'Geocoding';
    }

    /** @test */
    public function redis_geocoding_geoadd_single()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        // Add San Francisco to the geospatial key should only do it once
        $this->assertEquals(1, $this->redis->geoAdd($this->key, $this->longitude, $this->latitude, $this->location));
        $this->assertEquals(0, $this->redis->geoAdd($this->key, $this->longitude, $this->latitude, $this->location));
        // Cleanup used keys
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_geocoding_geohash_single()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        // Add San Francisco to the geospatial key should only do it once
        $this->assertEquals(1, $this->redis->geoAdd($this->key, $this->longitude, $this->latitude, $this->location));
        $this->assertEquals('9q8yyh27wv0', $this->redis->geoHash($this->key, $this->location)[0]);
        // Cleanup used keys
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_geocoding_geopos_single()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        // Add San Francisco to the geospatial key should only do it once
        $this->assertEquals(1, $this->redis->geoAdd($this->key, $this->longitude, $this->latitude, $this->location));
        $geoPos = $this->redis->geoPos($this->key, $this->location);
        $this->assertLessThanOrEqual($this->longitude * 0.99, $geoPos[0][0]);
        $this->assertGreaterThanOrEqual($this->latitude * 0.99, $geoPos[0][1]);
        // Cleanup used keys
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
    }

    /** @test */
    public function redis_geocoding_geodist_single()
    {
        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        // Add San Francisco to the geospatial key should only do it once
        $this->assertEquals(1, $this->redis->geoAdd($this->key, $this->longitude, $this->latitude, $this->location));
        $this->assertEquals(1, $this->redis->geoAdd($this->key, -73.935242, 40.730610, 'New York'));
        $geoDist = $this->redis->geoDist($this->key, $this->location, 'New York');
        $this->assertEquals(4136721.6835, $geoDist);
        // Cleanup used keys
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
    }
}
