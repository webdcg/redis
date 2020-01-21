### [Redis client for PHP using the PhpRedis C Extension](../README.md)
# [HyperLogLogs](docs/hyperloglogs.md)

|Command            |Description                                                                                |Supported              |Tested                 |Class/Trait    |Method     |
|---                |---                                                                                        |:-:                    |:-:                    |---            |---        |
|[pfAdd](#pfAdd)    |Adds the specified elements to the specified HyperLogLog.                                  |:white\_check\_mark:   |:white\_check\_mark:   |HyperLogLogs   |pfAdd      |
|[pfCount](#pfCount)|Return the approximated cardinality of the set(s) observed by the HyperLogLog at key(s).   |:white\_check\_mark:   |:white\_check\_mark:   |HyperLogLogs   |pfCount    |
|[pfMerge](#pfMerge)|Adds the specified elements to the specified HyperLogLog.                                  |:white\_check\_mark:   |:white\_check\_mark:   |HyperLogLogs   |pfMerge    |

## pfAdd

_**Description**_: Adds the specified elements to the specified HyperLogLog.

##### *Prototype*  

```php
public function pfAdd(string $key, array $elements): int {
    return $this->redis->pfAdd($key, $elements);
}
```

##### *Parameters*

- *key*: String. The HyperLogLog 
- *elements*: Array. Elements to be counted in the HyperLogLog. 

##### *Return value*

*int*: 1 if at least 1 HyperLogLog internal register was altered. 0 otherwise.

##### *Example*

```php
$redis->pfAdd('HyperLogLog', ['a', 'b', 'c']) // 1
$redis->pfAdd('HyperLogLog', ['a', 'c']) // 0
$redis->pfAdd('HyperLogLog', ['b', 'd']) // 1
```

## pfCount

_**Description**_: Return the approximated cardinality of the set(s) observed by the HyperLogLog at key(s).

##### *Prototype*  

```php
public function pfCount(...$keys): int {
    return $this->redis->pfCount(...$keys);
}
```

##### *Parameters*

- *keys*: String(s). The HyperLogLogs to be Counted.

##### *Return value*

*int*: The approximated number of unique elements observed via pfAdd.

##### *Example*

```php
$redis->pfAdd('HyperLogLog', ['a', 'b', 'c']) // 1
$redis->pfCount('HyperLogLog') // 3
```

## pfMerge

_**Description**_: Merge N different HyperLogLogs into a single one.

##### *Prototype*  

```php
public function pfMerge(string $destKey, array $sourceKeys): bool {
    return $this->redis->pfMerge($destKey, $sourceKeys);
}
```

##### *Parameters*

- *destKey*: String. The HyperLogLogs contain all Counted Elements.
- *sourceKeys*: Array. The HyperLogLogs to be Merged.

##### *Return value*

*bool*: TRUE on success, FALSE on error.

##### *Example*

```php
$redis->pfAdd('HyperLogLog', ['a', 'b', 'c']) // 1
$redis->pfCount('HyperLogLog') // 3

$redis->pfAdd('HyperLogLog2', ['b', 'd']) // 1
$redis->pfCount('HyperLogLog2') // 1

$redis->pfMerge('HyperLogLogMerged', ['HyperLogLog', 'HyperLogLog2']);
$redis->pfCount('HyperLogLogMerged') // 4
```
