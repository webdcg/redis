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

## Usage

```php
$redis = new Webdcg\Redis\Redis;

$options = ['WITHDIST'];
$redis->geoAdd('Geocoding', -122.431, 37.773, 'San Francisco');
$redis->geoAdd('Geocoding', -73.935242, 40.730610, 'New York');
$redis->geoHash('Geocoding', 'San Francisco');
$redis->geoPos('Geocoding', 'San Francisco');
$redis->geoDist('Geocoding', 'San Francisco', 'New York');
$redis->geoRadius("Geocoding", -157.858, 21.306, 300, 'mi', $options)
```

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
$redis->geoPos('Geocoding', 'San Francisco'); // [0 => [ 0 => -122.431, 1 => 37.773]]]
```

## [geoDist](https://redis.io/commands/geoDist)

_**Description**_: Return the distance between two members in a geospatial set. If units are passed it must be one of the following values:  
- 'm' => Meters
- 'km' => Kilometers
- 'mi' => Miles
- 'ft' => Feet

##### *Prototype*  

```php
public function geoDist(string $key, string $member1, string $member2, string $unit = 'm'): float {
    return $this->redis->geoDist($key, $member1, $member2, $unit);
}
```

##### *Parameters*

- *key*: String. The GeoSpatial index.
- *member1*: String. First location.
- *member2*: String. Second location.
- *unit*: String. Distance unit [m, km, mi, ft].

##### *Return value*

*float*: The distance between the two passed members in the units requested (meters by default).

##### *Example*

```php
$redis->geoAdd('Geocoding', -122.431, 37.773, 'San Francisco');  // 1
$redis->geoAdd('Geocoding', -73.935242, 40.730610, 'New York');  // 1
$redis->geoDist('Geocoding', 'San Francisco', 'New York'); // 4136721.6835
```

## [geoRadius](https://redis.io/commands/georadius)

_**Description**_: Return members of a set with geospatial information that are within the radius specified by the caller.

##### *Prototype*  

```php
public function geoRadius(
        string $key,
        float $longitude,
        float $latitude,
        float $radius,
        string $unit = 'm',
        ?array $options
    ): array {
    return $this->redis->geoRadius($key, $longitude, $latitude, $radius, $unit);
}
```

##### *Parameters*

- *key*: String. The GeoSpatial index.
- *longitude*: Float. Valid longitudes are from -180 to 180 degrees.
- *latitude*: Float. Valid latitudes are from -85.05112878 to 85.05112878 degrees.
- *radius*: Float. 
- *unit*: String. Distance unit [m, km, mi, ft].
- *options*: Array. _See bellow_

##### *Options Array*
The georadius command can be called with various options that control how Redis returns results.  The following table describes the options phpredis supports.  All options are case insensitive.  

| Key       | Value       | Description
| :---      | :---        | :---- |
| COUNT     | integer > 0 | Limit how many results are returned
|           | WITHCOORD   | Return longitude and latitude of matching members
|           | WITHDIST    | Return the distance from the center
|           | WITHHASH    | Return the raw geohash-encoded score
|           | ASC         | Sort results in ascending order
|           | DESC        | Sort results in descending order
| STORE     | _key_       | Store results in _key_
| STOREDIST | _key_       | Store the results as distances in _key_

 *Note*:  It doesn't make sense to pass both `ASC` and `DESC` options but if both are passed the last one passed will be used.  
 *Note*:  When using `STORE[DIST]` in Redis Cluster, the store key must has to the same slot as the query key or you will get a `CROSSLOT` error.

##### *Return value*

*float*: The distance between the two passed members in the units requested (meters by default).

##### *Example*

```php
/* Add some cities */
$redis->geoAdd("hawaii", -157.858, 21.306, "Honolulu", -156.331, 20.798, "Maui");

echo "Within 300 miles of Honolulu:\n";
var_dump($redis->geoRadius("hawaii", -157.858, 21.306, 300, 'mi'));

echo "\nWithin 300 miles of Honolulu with distances:\n";
$options = ['WITHDIST'];
var_dump($redis->geoRadius("hawaii", -157.858, 21.306, 300, 'mi', $options));

echo "\nFirst result within 300 miles of Honolulu with distances:\n";
$options['count'] = 1;
var_dump($redis->geoRadius("hawaii", -157.858, 21.306, 300, 'mi', $options));

echo "\nFirst result within 300 miles of Honolulu with distances in descending sort order:\n";
$options[] = 'DESC';
var_dump($redis->geoRadius("hawaii", -157.858, 21.306, 300, 'mi', $options));
```

##### *Output*
```
Within 300 miles of Honolulu:
array(2) {
  [0]=>
  string(8) "Honolulu"
  [1]=>
  string(4) "Maui"
}

Within 300 miles of Honolulu with distances:
array(2) {
  [0]=>
  array(2) {
    [0]=>
    string(8) "Honolulu"
    [1]=>
    string(6) "0.0002"
  }
  [1]=>
  array(2) {
    [0]=>
    string(4) "Maui"
    [1]=>
    string(8) "104.5615"
  }
}

First result within 300 miles of Honolulu with distances:
array(1) {
  [0]=>
  array(2) {
    [0]=>
    string(8) "Honolulu"
    [1]=>
    string(6) "0.0002"
  }
}

First result within 300 miles of Honolulu with distances in descending sort order:
array(1) {
  [0]=>
  array(2) {
    [0]=>
    string(4) "Maui"
    [1]=>
    string(8) "104.5615"
  }
}
```
