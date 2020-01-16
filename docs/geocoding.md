### [Redis client for PHP using the PhpRedis C Extension](../README.md)
# [Geocoding](docs/geocoding.md)

|Command                                |Description                                                                                                                                                    |Supported  |Tested     |Class/Trait    |Method         |
|---                                    |---                                                                                                                                                            |:-:        |:-:        |---            |---            |
|[geoAdd](#geoAdd)                      |Add one or more geospatial items to the specified key.                                                                                                         |:x:        |:x:        |Geocoding   |geoAdd          |
|[geoHash](#geoHash)                    |Retrieve Geohash strings for one or more elements of a geospatial index.                                                                                       |:x:        |:x:        |Geocoding   |geoHash          |
|[geoPos](#geoPos)                      |Return longitude, latitude positions for each requested member.                                                                                                |:x:        |:x:        |Geocoding   |geoPos          |
|[geoDist](#geoDist)                    |Return the distance between two members in a geospatial set.                                                                                                   |:x:        |:x:        |Geocoding   |geoDist          |
|[geoRadius](#geoRadius)                |Return members of a set with geospatial information that are within the radius specified by the caller.                                                        |:x:        |:x:        |Geocoding   |geoRadius          |
|[geoRadiusByMember](#geoRadiusByMember)|This method is identical to geoRadius except that instead of passing a longitude and latitude as the "source" you pass an existing member in the geospatial set.|:x:        |:x:        |Geocoding   |geoRadiusByMember          |
