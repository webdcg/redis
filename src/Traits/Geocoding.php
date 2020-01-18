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

    public function geoHash(): bool
    {
        return false;
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
