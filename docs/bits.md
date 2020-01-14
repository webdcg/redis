### [Redis client for PHP using the PhpRedis C Extension](../README.md)

# [Bits](docs/bits.md)

|Command                |Description                                                            |Supported              |Tested                 |Class/Trait    |Method     |
|---                    |---                                                                    |:-:                    |:-:                    |---            |---        |
|[bitCount](#bitCount)  |Count set bits in a string                                             |:white\_check\_mark:   |:white\_check\_mark:   |Bits           |bitCount   |
|[bitOp](#bitOp)        |Perform bitwise operations between strings                             |:x:                    |:x:                    |Bits           |bitOp   |
|[getBit](#getBit)      |Returns the bit value at offset in the string value stored at key      |:x:                    |:x:                    |Bits           |getBit   |
|[setBit](#setBit)      |Sets or clears the bit at offset in the string value stored at key     |:x:                    |:x:                    |Bits           |setBit   |

## Usage

```php
$redis = new Webdcg\Redis\Redis;

// Count set bits in a string
$redis->bitCount('key');
```

## bitCount

_**Description**_: Count set bits in a string.

##### *Prototype*  

```php
public function bitCount(string $key) : int {
    return $this->redis->bitCount($key);
}
```

##### *Parameters*

- *key*: String. The key append to.

##### *Return value*

*int*: Total of bits set in the string.

##### *Example*

```php
$redis->set('key', 'a');
$redis->bitCount('key'); // 3
```
