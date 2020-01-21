### [Redis client for PHP using the PhpRedis C Extension](../README.md)

# [Bits](docs/bits.md)

|Command                |Description                                                        |Supported              |Tested                 |Class/Trait    |Method     |
|---                    |---                                                                |:-:                    |:-:                    |---            |---        |
|[bitCount](#bitCount)  |Count set bits in a string                                         |:white\_check\_mark:   |:white\_check\_mark:   |Bits           |bitCount   |
|[bitField](#bitField)  |Perform arbitrary bitfield integer operations on strings           |:x:                    |:x:                    |Bits           |bitField   |
|[bitOp](#bitOp)        |Perform bitwise operations between strings                         |:white\_check\_mark:   |:white\_check\_mark:   |Bits           |bitOp      |
|[bitPos](#bitPos)      |Find first bit set or clear in a string                            |:x:                    |:x:                    |Bits           |bitPos     |
|[getBit](#getBit)      |Returns the bit value at offset in the string value stored at key  |:white\_check\_mark:   |:white\_check\_mark:   |Bits           |getBit     |
|[setBit](#setBit)      |Sets or clears the bit at offset in the string value stored at key |:white\_check\_mark:   |:white\_check\_mark:   |Bits           |setBit     |

## Usage

```php
$redis = new Webdcg\Redis\Redis;

$redis->bitCount('key');
$redis->bitField('key');
$redis->bitPos('key');
$redis->bitOp('key');
$redis->getBit('key');
$redis->setBit('key');
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
$redis->set('key', 'a'); // 1100001 in Binary or 97 in decimal
$redis->bitCount('key'); // 3
```

## bitField

_**Description**_: Perform arbitrary bitfield integer operations on strings

##### *Prototype*  

```php
public function bitField(): bool {
    return false;
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

- *operation*: String. Bitwise operation to perform: AND, OR, NOT, XOR
- *returnKey*: String. The key where the result will be saved..
- *keys*: String(s). The key(s) part of he operation.

##### *Return value*

*int*: The size of the string stored in the destination key.

##### *Example*

```php
$redis->bitOp('not', 'testBitOpNot', 'testBit');

$redis->set('testBit1', 0);
$redis->set('testBit2', 1);
$redis->bitOp('and', 'testBitOpAnd', 'testBit1', 'testBit2');
$redis->get('testBitOpAnd'); // 0 since only the two bits that are common 
                            // between 0 and 1 will match
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
$redis->setBit('key', 1, 1);
$redis->setBit('key', 7, 1);
$redis->get('key'); // A => 01000001
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

- *key*: String. The Bitmap.
- *offset*: String. The bit position within the string.

##### *Return value*

*int*: 0 or 1

##### *Example*

```php
$redis->set('key', 'A'); // 01000001
$redis->getBit('key', 0); // 0
$redis->getBit('key', 1); // 1
```
