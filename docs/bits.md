### [Redis client for PHP using the PhpRedis C Extension](../README.md)

# [Bits](docs/bits.md)

- [bitCount](#bitCount) - Count set bits in a string
- [bitOp](#bitOp) - Perform bitwise operations between strings
- [getBit](#getBit) - Returns the bit value at offset in the string value stored at key
- [setBit](#setBit) - Sets or clears the bit at offset in the string value stored at key

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
$redis->set('key', 'value');
$redis->bitCount('key'); /* 12 */
```
