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

    /**
     * Return longitude, latitude positions for each requested member.
     *
     * @param  string $key
     * @param  string $member
     * @param  splat $members
     *
     * @return array            One or more longitude/latitude positions
     */
    public function geoPos(string $key, string $member, ...$members): array
    {
        return $this->redis->geoPos($key, $member);
    }

    /**
     * Return the distance between two members in a geospatial set. If units
     * are passed it must be one of the following values:.
     *
     * @param  string $key      [description]
     * @param  string $member1  [description]
     * @param  string $member2  [description]
     * @param  string $unit     [description]
     *
     * @return float            [description]
     */
    public function geoDist(string $key, string $member1, string $member2, string $unit = 'm'): float
    {
        return $this->redis->geoDist($key, $member1, $member2, $unit);
    }

    /**
     * Return members of a set with geospatial information that are within the
     * radius specified by the caller.
     *
     * @param  string $key       [description]
     * @param  float  $longitude [description]
     * @param  float  $latitude  [description]
     * @param  float  $radius    [description]
     * @param  string $unit      [description]
     * @param  array  $options   [description]
     *
     * @return array            When no STORE option is passed, this function returns an array of results.
     */
    public function geoRadius(
        string $key,
        float $longitude,
        float $latitude,
        float $radius,
        string $unit = 'm',
        ?array $options = []
    ): array {
        return $this->redis->geoRadius($key, $longitude, $latitude, $radius, $unit);
    }

    public function geoRadiusByMember(): bool
    {
        return false;
    }
}
