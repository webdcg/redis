### [Redis client for PHP using the PhpRedis C Extension](../README.md)
# [Sorted Sets](docs/sorted-sets.md)

|Command                                    |Description                                                                                |Supported  |Tested     |Class/Trait    |Method             |
|---                                        |---                                                                                        |:-:        |:-:        |---            |---                |
|[bzPop](#bzPop)                            |Block until Redis can pop the highest or lowest scoring member from one or more ZSETs.     |:x:        |:x:        |SortedSets     |bzPop              |
|[zAdd](#zAdd)                              |Add one or more members to a sorted set or update its score if it already exists.          |:white\_check\_mark:        |:white\_check\_mark:        |SortedSets     |zAdd               |
|[zCard](#zCard)                            |Get the number of members in a sorted set.                                                 |:x:        |:x:        |SortedSets     |zCard              |
|[zSize](#zSize)                            |Get the number of members in a sorted set.                                                 |:x:        |:x:        |SortedSets     |zSize              |
|[zCount](#zCount)                          |Count the members in a sorted set with scores within the given values.                     |:x:        |:x:        |SortedSets     |zCount             |
|[zIncrBy](#zIncrBy)                        |Increment the score of a member in a sorted set.                                           |:x:        |:x:        |SortedSets     |zIncrBy            |
|[zinterstore](#zinterstore)                |Intersect multiple sorted sets and store the resulting sorted set in a new key.            |:x:        |:x:        |SortedSets     |zinterstore        |
|[zInter](#zInter)                          |Intersect multiple sorted sets and store the resulting sorted set in a new key.            |:x:        |:x:        |SortedSets     |zInter             |
|[zPop](#zPop)                              |Redis can pop the highest or lowest scoring member from one a ZSET.                        |:x:        |:x:        |SortedSets     |zPop               |
|[zRange](#zRange)                          |Return a range of members in a sorted set, by index.                                       |:x:        |:x:        |SortedSets     |zRange             |
|[zRangeByScore](#zRangeByScore)            |Return a range of members in a sorted set, by score.                                       |:x:        |:x:        |SortedSets     |zRangeByScore      |
|[zRevRangeByScore](#zRevRangeByScore)      |Return a range of members in a sorted set, by score.                                       |:x:        |:x:        |SortedSets     |zRevRangeByScore   |
|[zRangeByLex](#zRangeByLex)                |Return a lexicographical range from members that share the same score.                     |:x:        |:x:        |SortedSets     |zRangeByLex        |
|[zRank](#zRank)                            |Determine the index of a member in a sorted set.                                           |:x:        |:x:        |SortedSets     |zRank              |
|[zRevRank](#zRevRank)                      |Determine the index of a member in a sorted set.                                           |:x:        |:x:        |SortedSets     |zRevRank           |
|[zRem](#zRem)                              |Remove one or more members from a sorted set.                                              |:x:        |:x:        |SortedSets     |zRem               |
|[zDelete](#zDelete)                        |Remove one or more members from a sorted set.                                              |:x:        |:x:        |SortedSets     |zDelete            |
|[zRemove](#zRemove)                        |Remove one or more members from a sorted set.                                              |:x:        |:x:        |SortedSets     |zRemove            |
|[zRemRangeByRank](#zRemRangeByRank)        |Remove all members in a sorted set within the given indexes.                               |:x:        |:x:        |SortedSets     |zRemRangeByRank    |
|[zDeleteRangeByRank](#zDeleteRangeByRank)  |Remove all members in a sorted set within the given indexes.                               |:x:        |:x:        |SortedSets     |zDeleteRangeByRank |
|[zRemRangeByScore](#zRemRangeByScore)      |Remove all members in a sorted set within the given scores.                                |:x:        |:x:        |SortedSets     |zRemRangeByScore   |
|[zDeleteRangeByScore](#zDeleteRangeByScore)|Remove all members in a sorted set within the given scores.                                |:x:        |:x:        |SortedSets     |zDeleteRangeByScore|
|[zRemoveRangeByScore](#zRemoveRangeByScore)|Remove all members in a sorted set within the given scores.                                |:x:        |:x:        |SortedSets     |zRemoveRangeByScore|
|[zRevRange](#zRevRange)                    |Return a range of members in a sorted set, by index, with scores ordered from high to low. |:x:        |:x:        |SortedSets     |zRevRange          |
|[zScore](#zScore)                          |Get the score associated with the given member in a sorted set.                            |:x:        |:x:        |SortedSets     |zScore             |
|[zunionstore](#zunionstore)                |Add multiple sorted sets and store the resulting sorted set in a new key.                  |:x:        |:x:        |SortedSets     |zunionstore        |
|[zUnion](#zUnion)                          |Add multiple sorted sets and store the resulting sorted set in a new key.                  |:x:        |:x:        |SortedSets     |zUnion             |
|[zScan](#zScan)                            |Scan a sorted set for members.                                                             |:x:        |:x:        |SortedSets     |zScan              |
