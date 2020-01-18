### [Redis client for PHP using the PhpRedis C Extension](../README.md)
# [Geocoding](docs/geocoding.md)

|Command                |Description                                                                                            |Supported  |Tested     |Class/Trait    |Method     |
|---                    |---                                                                                                    |:-:        |:-:        |---            |---        |
|[geoAdd](#geoAdd)      |Add one or more geospatial items to the specified key.                                                 |:x:        |:x:        |Geocoding      |geoAdd     |
|[geoHash](#geoHash)    |Retrieve Geohash strings for one or more elements of a geospatial index.                               |:x:        |:x:        |Geocoding      |geoHash    |
|[geoPos](#geoPos)      |Return longitude, latitude positions for each requested member.                                        |:x:        |:x:        |Geocoding      |geoPos     |
|[geoDist](#geoDist)    |Return the distance between two members in a geospatial set.                                           |:x:        |:x:        |Geocoding      |geoDist    |
|[geoRadius](#geoRadius)|Return members of a set with geospatial information that are within the radius specified by the caller.|:x:        |:x:        |Geocoding      |geoRadius  |
|[geoRadiusByMember](#geoRadiusByMember)|This method is identical to geoRadius except that instead of passing a longitude and latitude as the "source" you pass an existing member in the geospatial set.|:x:|:x:|Geocoding|geoRadiusByMember|

## [geoAdd](https://redis.io/commands/geoadd)

_**Description**_: Add one or more geospatial items to the specified key. This function must be called with at least one longitude, latitude, member triplet.

##### *Prototype*  

```php
public function geoAdd(string $key, float $longitude, float $latitude, string $member, ...$locations): int {
    return $this->redis->geoAdd($key, $longitude, $latitude, $member, ...$locations);
}
```

##### *Parameters*

- *key*: String. The GeoSpatial index.
- *longitude*: Float. Valid longitudes are from -180 to 180 degrees.
- *latitude*: Float. Valid latitudes are from -85.05112878 to 85.05112878 degrees.
- *member*: String: Location name.
- *locations*: Zero or more triplets of Longitude, Latitude and Member.

##### *Return value*

*int*: The number of elements added to the geospatial key.

##### *Example*

```php
$redis->geoAdd('Geocoding', -122.431, 37.773, 'San Francisco');  // 1
$redis->geoAdd('Geocoding', -122.431, 37.773, 'San Francisco');  // 0
```

## [geoHash](https://redis.io/commands/geohash)

_**Description**_: Retrieve Geohash strings for one or more elements of a geospatial index.

##### *Prototype*  

```php
public function geoHash(string $key, string $member, ...$members): array {
    return $this->redis->geoHash($key, $member);
}
```

##### *Parameters*

- *key*: String. The GeoSpatial index.
- *member*: String: Location name.
- *members*: Zero or more Members.

##### *Return value*

*array*: One or more Redis Geohash encoded strings.

##### *Example*

```php
$redis->geoAdd('Geocoding', -122.431, 37.773, 'San Francisco');  // 1
$redis->geoHash('Geocoding', 'San Francisco'); // [0 => '9q8yyh27wv0']
```

## [geoPos](https://redis.io/commands/geopos)

_**Description**_: Return longitude, latitude positions for each requested member.

##### *Prototype*  

```php
public function geoPos(string $key, string $member, ...$members): array {
    return $this->redis->geoPos($key, $member);
}
```

##### *Parameters*

- *key*: String. The GeoSpatial index.
- *member*: String: Location name.
- *members*: Zero or more Members.

##### *Return value*

*array*: Return longitude, latitude positions for each requested member.

##### *Example*

```php
$redis->geoAdd('Geocoding', -122.431, 37.773, 'San Francisco');  // 1
$redis->geoHash('Geocoding', 'San Francisco'); // [0 => '9q8yyh27wv0']
```
