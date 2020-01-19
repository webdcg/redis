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

    /** @test */
    public function redis_geocoding_georadius_single()
    {
        $california = [
            0 => [
                'longitude' => -121.478851,
                'latitude' => 38.575764,
                'location' => 'Sacramento'
            ],
            1 => [
                'longitude' => -121.893028,
                'latitude' => 37.335480,
                'location' => 'San Jose'
            ],
            2 => [
                'longitude' => -118.243683,
                'latitude' => 34.052235,
                'location' => 'Los Angeles'
            ],
        ];

        // Start from scratch
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
        // Add San Francisco to the geospatial key should only do it once
        $this->assertEquals(1, $this->redis->geoAdd($this->key, $this->longitude, $this->latitude, $this->location));

        for ($i = 0; $i < count($california); $i++) {
            $this->assertEquals(1, $this->redis->geoAdd($this->key, $california[$i]['longitude'], $california[$i]['latitude'], $california[$i]['location']));
        }
        
        $geoRadius = $this->redis->geoRadius($this->key, $this->longitude, $this->latitude, 100, 'km');
        $this->assertEquals('San Francisco', $geoRadius[0]);
        $this->assertEquals('San Jose', $geoRadius[1]);

        // Cleanup used keys
        $this->assertGreaterThanOrEqual(0, $this->redis->delete($this->key));
    }
}
