### [Redis client for PHP using the PhpRedis C Extension](../README.md)

# [Bits](docs/bits.md)

|Command                |Description                                                            |Supported              |Tested                 |Class/Trait    |Method     |
|---                    |---                                                                    |:-:                    |:-:                    |---            |---        |
|[bitCount](#bitCount)  |Count set bits in a string                                             |:white\_check\_mark:   |:white\_check\_mark:   |Bits           |bitCount   |
|[bitOp](#bitOp)        |Perform bitwise operations between strings                             |:white\_check\_mark:   |:white\_check\_mark:   |Bits           |bitOp      |
|[getBit](#getBit)      |Returns the bit value at offset in the string value stored at key      |:white\_check\_mark:   |:white\_check\_mark:   |Bits           |getBit     |
|[setBit](#setBit)      |Sets or clears the bit at offset in the string value stored at key     |:white\_check\_mark:   |:white\_check\_mark:   |Bits           |setBit     |

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

## bitOp

_**Description**_: Perform bitwise operations between strings.

##### *Prototype*  

```php
public function bitOp(string $operation, string $returnKey, ...$keys): int {
    return $this->redis->bitOp($key);
}
```

##### *Parameters*

- *operation*: String. The key append to.
- *returnKey*: String. The key append to.
- *keys*: String. The key append to.

##### *Return value*

*int*: Total of bits set in the string.

##### *Example*

```php
$redis->set('key', 'a');
$redis->bitCount('key'); // 3
```

## setBit

_**Description**_: Sets or clears the bit at offset in the string value stored at key.

##### *Prototype*  

```php
public function setBit(string $key, int $offset, int $value): int {
    return $this->redis->setBit($key, $offset, $value);
}
```

##### *Parameters*

- *operation*: String. The key append to.
- *returnKey*: String. The key append to.
- *keys*: String. The key append to.

##### *Return value*

*int*: Total of bits set in the string.

##### *Example*

```php
$redis->set('key', 'a');
$redis->bitCount('key'); // 3
```

## getBit

_**Description**_: Returns the bit value at offset in the string value stored at key.

##### *Prototype*  

```php
public function getBit(string $key, int $offset) : int {
    return $this->redis->getBit($key, $offset);
}
```

##### *Parameters*

- *key*: String. The key append to.
- *offset*: String. The key append to.

##### *Return value*

*int*: Total of bits set in the string.

##### *Example*

```php
$redis->set('key', 'a');
$redis->bitCount('key'); // 3
```
