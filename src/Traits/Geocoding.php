<?php

namespace Webdcg\Redis\Traits;

trait Geocoding
{
    /**
     * Add one or more geospatial items to the specified key. This function must
     * be called with at least one longitude, latitude, member triplet.
     *
     * @param  string $key
     * @param  float  $longitude
     * @param  float  $latitude
     * @param  string $member
     * @param  $locations
     *
     * @return int                  The number of elements added to the geospatial key.
     */
    public function geoAdd(string $key, float $longitude, float $latitude, string $member, ...$locations): int
    {
        return $this->redis->geoAdd($key, $longitude, $latitude, $member, ...$locations);
    }

    /**
     * Retrieve Geohash strings for one or more elements of a geospatial index.
     *
     * @param  string $key
     * @param  string $member
     * @param  splat  $members
     *
     * @return array            One or more Redis Geohash encoded strings.
     */
    public function geoHash(string $key, string $member, ...$members): array
    {
        return $this->redis->geoHash($key, $member);
    }

    public function geoPos(): bool
    {
        return false;
    }

    public function geoDist(): bool
    {
        return false;
    }

    public function geoRadius(): bool
    {
        return false;
    }

    public function geoRadiusByMember(): bool
    {
        return false;
    }
}
