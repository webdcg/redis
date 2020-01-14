### [Redis client for PHP using the PhpRedis C Extension](../README.md)

# [Strings](docs/strings.md)

|Command                        |Description                                                            |Supported              |Tested                 |Class/Trait    |Method     |
|---                            |---                                                                    |:-:                    |:-:                    |---            |---        |
|[append](#append)              |Append a value to a key                                                |:x:   |:x:   |Strings        |append   |
|[decr](#decr)                  |Decrement the value of a key                                           |:x:   |:x:   |Strings        |decr   |
|[decrBy](#decrBy)              |Decrement the value of a key                                           |:x:   |:x:   |Strings        |decrBy   |
|[get](#get)                    |Get the value of a key                                                 |:x:   |:x:   |Strings        |get   |
|[getRange](#getRange)          |Get a substring of the string stored at a key                          |:x:   |:x:   |Strings        |getRange   |
|[getSet](#getSet)              |Set the string value of a key and return its old value                 |:x:   |:x:   |Strings        |getSet   |
|[incr](#incr)                  |Increment the value of a key                                           |:x:   |:x:   |Strings        |incr   |
|[incrBy](#incrBy)              |Increment the value of a key                                           |:x:   |:x:   |Strings        |incrBy   |
|[incrByFloat](#incrByFloat)    |Increment the float value of a key by the given amount                 |:x:   |:x:   |Strings        |incrByFloat   |
|[mGet](#mGet)                  |Get the values of all the given keys                                   |:x:   |:x:   |Strings        |mGet   |
|[getMultiple](#getMultiple)    |Get the values of all the given keys                                   |:x:   |:x:   |Strings        |getMultiple   |
|[mSet](#mSet)                  |Set multiple keys to multiple values                                   |:x:   |:x:   |Strings        |mSet   |
|[mSetNX](#mSetNX)              |Set multiple keys to multiple values                                   |:x:   |:x:   |Strings        |mSetNX   |
|[set](#set)                    |Set the string value of a key                                          |:x:   |:x:   |Strings        |set   |
|[setEx](#setEx)                |Set the value and expiration of a key                                  |:x:   |:x:   |Strings        |setEx   |
|[pSetEx](#pSetEx)              |Set the value and expiration of a key                                  |:x:   |:x:   |Strings        |pSetEx   |
|[setNx](#setNx)                |Set the value of a key, only if the key does not exist                 |:x:   |:x:   |Strings        |setNx   |
|[setRange](#setRange)          |Overwrite part of a string at key starting at the specified offset     |:x:   |:x:   |Strings        |setRange   |
|[strLen](#strLen)              |Get the length of the value stored in a key                            |:x:   |:x:   |Strings        |strLen   |

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
