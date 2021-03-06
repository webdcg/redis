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
     * @param  string $key
     * @param  string $member1
     * @param  string $member2
     * @param  string $unit
     *
     * @return float
     */
    public function geoDist(string $key, string $member1, string $member2, string $unit = 'm'): float
    {
        return $this->redis->geoDist($key, $member1, $member2, $unit);
    }

    /**
     * Return members of a set with geospatial information that are within the
     * radius specified by the caller.
     *
     * @param  string $key
     * @param  float  $longitude
     * @param  float  $latitude
     * @param  float  $radius
     * @param  string $unit
     * @param  array  $options
     *
     * @return array            When no STORE option is passed, this function
     *                          returns an array of results.
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

    /**
     * This method is identical to geoRadius except that instead of passing a
     * longitude and latitude as the "source" you pass an existing member in
     * the geospatial set.
     *
     * @param  string $key
     * @param  string $member
     * @param  float  $radius
     * @param  string $unit
     * @param  array  $options
     *
     * @return array
     */
    public function geoRadiusByMember(
        string $key,
        string $member,
        float $radius,
        string $unit,
        ?array $options = []
    ): array {
        return $this->redis->geoRadiusByMember($key, $member, $radius, $unit);
    }
}
