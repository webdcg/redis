### [Redis client for PHP using the PhpRedis C Extension](../README.md)

# [Strings](docs/strings.md)

|Command                        |Description                                                            |Supported              |Tested                 |Class/Trait    |Method     |
|---                            |---                                                                    |:-:                    |:-:                    |---            |---        |
|[append](#append)              |Append a value to a key                                                |:white\_check\_mark:   |:white\_check\_mark:   |Strings        |append   |
|[decr](#decr)                  |Decrement the value of a key                                           |:white\_check\_mark:   |:white\_check\_mark:   |Strings        |decr   |
|[decrBy](#decrBy)              |Decrement the value of a key                                           |:white\_check\_mark:   |:white\_check\_mark:   |Strings        |decrBy   |
|[get](#get)                    |Get the value of a key                                                 |:x:   |:x:   |Strings        |get   |
|[getRange](#getRange)          |Get a substring of the string stored at a key                          |:white\_check\_mark:   |:white\_check\_mark:   |Strings        |getRange   |
|[getSet](#getSet)              |Set the string value of a key and return its old value                 |:white\_check\_mark:   |:white\_check\_mark:   |Strings        |getSet   |
|[incr](#incr)                  |Increment the value of a key                                           |:white\_check\_mark:   |:white\_check\_mark:   |Strings        |incr   |
|[incrBy](#incrBy)              |Increment the value of a key                                           |:white\_check\_mark:   |:white\_check\_mark:   |Strings        |incrBy   |
|[incrByFloat](#incrByFloat)    |Increment the float value of a key by the given amount                 |:white\_check\_mark:   |:white\_check\_mark:   |Strings        |incrByFloat   |
|[mGet](#mGet)                  |Get the values of all the given keys                                   |:white\_check\_mark:   |:white\_check\_mark:   |Strings        |mGet   |
|[getMultiple](#getMultiple)    |Get the values of all the given keys                                   |:white\_check\_mark:   |:white\_check\_mark:   |Strings        |getMultiple   |
|[mSet](#mSet)                  |Set multiple keys to multiple values                                   |:x:   |:x:   |Strings        |mSet   |
|[mSetNX](#mSetNX)              |Set multiple keys to multiple values                                   |:x:   |:x:   |Strings        |mSetNX   |
|[set](#set)                    |Set the string value of a key                                          |:x:   |:x:   |Strings        |set   |
|[setEx](#setEx)                |Set the value and expiration of a key                                  |:x:   |:x:   |Strings        |setEx   |
|[pSetEx](#pSetEx)              |Set the value and expiration of a key                                  |:x:   |:x:   |Strings        |pSetEx   |
|[setNx](#setNx)                |Set the value of a key, only if the key does not exist                 |:x:   |:x:   |Strings        |setNx   |
|[setRange](#setRange)          |Overwrite part of a string at key starting at the specified offset     |:white\_check\_mark:   |:white\_check\_mark:   |Strings        |setRange   |
|[strLen](#strLen)              |Get the length of the value stored in a key                            |:white\_check\_mark:   |:white\_check\_mark:   |Strings        |strLen   |

## Usage

```php
$redis = new Webdcg\Redis\Redis;

// Simple key -> value set
$redis->set('key', 'value');
// Will redirect, and actually make an SETEX call
$redis->set('key', 'value', 10);
// Will set the key, if it doesn't exist, with a ttl of 10 seconds
$redis->set('key:'.time(), 'value', ['nx', 'ex' => 10]);
// Will set a key, if it does exist, with a ttl of 1000 miliseconds
$redis->set('key', 'value', ['xx', 'px' => 1000]);
$redis->setEx('key', 10, 'value');
```

## [append](https://redis.io/commands/append)

_**Description**_: Append specified string to the string stored in specified key.

##### *Prototype*  

```php
public function append(string $key, string $value) : int {
    return $this->redis->append($key, $value);
}
```

##### *Parameters*

- *key*: String. The key append to.
- *value*: String. The value to be appended.

##### *Return value*

*int*: Size of the value after the append.

##### *Example*

```php
$redis->set('key', 'value1');
$redis->append('key', 'value2'); /* 12 */
$redis->get('key'); /* 'value1value2' */
```

## [decr](https://redis.io/commands/decr)

_**Description**_: Decrement the number stored at key by one.

##### *Prototype*  

```php
public function decr(string $key): int {
    return $this->redis->decr($key);
}
```

##### *Parameters*

- *key*: String. The key to be decremented.

##### *Return value*

*int*: the new value.

##### *Example*

```php
$redis->set('key', 1);
$redis->decr('key'); // 0
```

## [decrBy](https://redis.io/commands/decrby)

_**Description**_: Decrement the number stored at key by the given decrement.

##### *Prototype*  

```php
public function decrBy(string $key, int $decrement): int {
    return $this->redis->decrBy($key, $decrement);
}
```

##### *Parameters*

- *key*: String. The key to be decremented.
- *decrement*: Int. The value to be decremented.

##### *Return value*

*int*: The new value.

##### *Example*

```php
$redis->set('key', 5);
$redis->decrBy('key', 3); // 2
```

## [getRange](https://redis.io/commands/getrange)

_**Description**_: Return a substring of a larger string.  
_Note_: substr also supported but deprecated in redis.

##### *Prototype*  

```php
public function getRange(string $key, int $start, int $end): string {
    return $this->redis->getRange($key, $start, $end);
}
```

##### *Parameters*

- *key*: String. The key append to.
- *start*: Int. The position to begin getting characters. (0 based, negative values start from the end at -1 for the last element).
- *end*: Int. The position to stop getting characters. (0 based, negative values start from the end at -1 for the last element).

##### *Return value*

*string*: the substring

##### *Example*

```php
$redis->set($this->key, 'Taylor Swift');
$redis->getRange($this->key, 0, 5); // Taylor
$redis->getRange($this->key, -5, -1); // Swift
```

## [getSet](https://redis.io/commands/getset)

_**Description**_: Sets a value and returns the previous entry at that key.

##### *Prototype*  

```php
public function getSet(string $key, string $value): string {
    return $this->redis->getSet($key, $value);
}
```

##### *Parameters*

- *key*: String. The key to be fetched and updated.
- *value*: String. The new value.

##### *Return value*

*string*: The previous value located at this key. 

##### *Example*

```php
$redis->set($this->key, 'Taylor Swift');
$redis->getSet($this->key, 'Milla Jovovich'); // Taylor Swift
```

## [incr](https://redis.io/commands/incr)

_**Description**_: Increment the number stored at key by one.

##### *Prototype*  

```php
public function incr(string $key): int {
    return $this->redis->incr($key);
}
```

##### *Parameters*

- *key*: String. The key to be incremented.

##### *Return value*

*int*: the new value.

##### *Example*

```php
$redis->set('key', 1);
$redis->incr('key'); // 2
```

## [incrBy](https://redis.io/commands/incrby)

_**Description**_: Increment the number stored at key by the given increment.

##### *Prototype*  

```php
public function incrBy(string $key, int $increment): int {
    return $this->redis->incrBy($key, $increment);
}
```

##### *Parameters*

- *key*: String. The key to be incremented.
- *increment*: Int. The value to be incremented.

##### *Return value*

*int*: The new value.

##### *Example*

```php
$redis->set('key', 2);
$redis->incrBy('key', 3); // 5
```

## [incrByFloat](https://redis.io/commands/incrbyfloat)

_**Description**_: Increment the number stored at key by the given increment.

##### *Prototype*  

```php
public function incrByFloat(string $key, int $increment): int {
    return $this->redis->incrByFloat($key, $increment);
}
```

##### *Parameters*

- *key*: String. The key to be incremented.
- *increment*: Int. The value to be incremented.

##### *Return value*

*int*: The new value.

##### *Example*

```php
$redis->set('key', 5);
$redis->incrByFloat('key', 3); // 2
```

## [mGet](https://redis.io/commands/mget)

_**Description**_: Get the values of all the specified keys. If one or more keys don't exist, the array will contain FALSE at the position of the key.

##### *Prototype*  

```php
public function mGet(array $keys): array {
    return $this->redis->mGet($keys);
}
```

##### *Parameters*

- *keys*: Array. All the keys required.

##### *Return value*

*array*: Array reply: list of values at the specified keys.

##### *Example*

```php
$redis->set('tswift', 'Taylor Swift');
$redis->set('millaj', 'Milla Jovovich');
$redis->set('kbeck', 'Kate Beckinsale');
$redis->mGet(['tswift', 'millaj', 'nonexisting']); // ['Taylor Swift', 'Milla Jovovich', 'Kate Beckinsale']
```

## [getMultiple](https://redis.io/commands/mget)

_**Description**_: Get the values of all the specified keys. If one or more keys don't exist, the array will contain FALSE at the position of the key.

_Note_: getMultiple is an alias for `mGet` and will be removed in future versions of phpredis.

##### *Prototype*  

```php
public function getMultiple(array $keys): array {
    return $this->redis->mGet($keys);
}
```

##### *Parameters*

- *keys*: Array. All the keys required.

##### *Return value*

*array*: Array reply: list of values at the specified keys.

##### *Example*

```php
$redis->set('tswift', 'Taylor Swift');
$redis->set('millaj', 'Milla Jovovich');
$redis->set('kbeck', 'Kate Beckinsale');
$redis->getMultiple(['tswift', 'millaj', 'kbeck']); // ['Taylor Swift', 'Milla Jovovich', 'Kate Beckinsale']
```

## [setRange](https://redis.io/commands/setrange)

_**Description**_: Changes a substring of a larger string.

##### *Prototype*  

```php
public function setRange(string $key, int $offset, string $value): int {
    return $this->redis->setRange($key, $start, $end);
}
```

##### *Parameters*

- *key*: String. The key append to.
- *start*: Int. The position to begin getting characters. (0 based, negative values start from the end at -1 for the last element).
- *end*: Int. The position to stop getting characters. (0 based, negative values start from the end at -1 for the last element).

##### *Return value*

*string*: the substring

##### *Example*

```php
$redis->set($this->key, 'Hello World');
$redis->setRange($this->key, 6, 'Redis'); // 11
$redis->get($this->key); // Hello Redis
```

## [strLen](https://redis.io/commands/strlen)

_**Description**_: Returns the length of the string value stored at key. An error is returned when key holds a non-string value.

##### *Prototype*  

```php
public function strLen(string $key, string $value) : int {
    return $this->redis->strLen($key, $value);
}
```

##### *Parameters*

- *key*: String. The key append to.
- *value*: String. The value to be appended.

##### *Return value*

*int*: Size of the value after the append.

##### *Example*

```php
$redis->set('key', 'value1');
$redis->append('key', 'value2'); /* 12 */
$redis->get('key'); /* 'value1value2' */
```