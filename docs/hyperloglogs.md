### [Redis client for PHP using the PhpRedis C Extension](../README.md)
# [HyperLogLogs](docs/hyperloglogs.md)

|Command            |Description                                                                                |Supported  |Tested     |Class/Trait    |Method         |
|---                |---                                                                                        |:-:        |:-:        |---            |---            |
|[pfAdd](#pfAdd)    |Adds the specified elements to the specified HyperLogLog.                                  |:x:        |:x:        |HyperLogLogs   |pfAdd          |
|[pfCount](#pfCount)|Return the approximated cardinality of the set(s) observed by the HyperLogLog at key(s).   |:x:        |:x:        |HyperLogLogs   |pfCount        |
|[pfMerge](#pfMerge)|Adds the specified elements to the specified HyperLogLog.                                  |:x:        |:x:        |HyperLogLogs   |pfMerge        |

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