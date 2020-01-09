### [Redis client for PHP using the PhpRedis C Extension](../README.md)

# [Strings](docs/strings.md)

- [append](#append) - Append a value to a key
- [decr](#decr), decrBy - Decrement the value of a key
- [get](#get) - Get the value of a key
- [getRange](#getRange) - Get a substring of the string stored at a key
- [getSet](#getSet) - Set the string value of a key and return its old value
- [incr](#incr), incrBy - Increment the value of a key
- [incrByFloat](#incrByFloat) - Increment the float value of a key by the given amount
- [mGet](#mGet), getMultiple - Get the values of all the given keys
- [mSet](#mSet), mSetNX - Set multiple keys to multiple values
- [set](#set) - Set the string value of a key
- [setEx](#setEx), pSetEx - Set the value and expiration of a key
- [setNx](#setNx) - Set the value of a key, only if the key does not exist
- [setRange](#setRange) - Overwrite part of a string at key starting at the specified offset
- [strLen](#strLen) - Get the length of the value stored in a key

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

## append

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
