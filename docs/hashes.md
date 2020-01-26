### [Redis client for PHP using the PhpRedis C Extension](../README.md)
# [Hashes](docs/hashes.md)

|Command                        |Description                                                            |Supported              |Tested                 |Class/Trait    |Method         |
|---                            |---                                                                    |:-:                    |:-:                    |---            |---            |
|[hDel](#hDel)                  |Delete one or more hash fields                                         |:x:                    |:x:                    |Hashes         |hDel           |
|[hExists](#hExists)            |Determine if a hash field exists                                       |:x:                    |:x:                    |Hashes         |hExists        |
|[hGet](#hGet)                  |Get the value of a hash field                                          |:x:                    |:x:                    |Hashes         |hGet           |
|[hGetAll](#hGetAll)            |Get all the fields and values in a hash                                |:x:                    |:x:                    |Hashes         |hGetAll        |
|[hIncrBy](#hIncrBy)            |Increment the integer value of a hash field by the given number        |:x:                    |:x:                    |Hashes         |hIncrBy        |
|[hIncrByFloat](#hIncrByFloat)  |Increment the float value of a hash field by the given amount          |:x:                    |:x:                    |Hashes         |hIncrByFloat   |
|[hKeys](#hKeys)                |Get all the fields in a hash                                           |:x:                    |:x:                    |Hashes         |hKeys          |
|[hLen](#hLen)                  |Get the number of fields in a hash                                     |:x:                    |:x:                    |Hashes         |hLen           |
|[hMGet](#hMGet)                |Get the values of all the given hash fields                            |:x:                    |:x:                    |Hashes         |hMGet          |
|[hMSet](#hMSet)                |Set multiple hash fields to multiple values                            |:x:                    |:x:                    |Hashes         |hMSet          |
|[hSet](#hSet)                  |Set the string value of a hash field                                   |:x:                    |:x:                    |Hashes         |hSet           |
|[hSetNx](#hSetNx)              |Set the value of a hash field, only if the field does not exist        |:x:                    |:x:                    |Hashes         |hSetNx         |
|[hVals](#hVals)                |Get all the values in a hash                                           |:x:                    |:x:                    |Hashes         |hVals          |
|[hScan](#hScan)                |Scan a hash key for members                                            |:x:                    |:x:                    |Hashes         |hScan          |
|[hStrLen](#hStrLen)            |Get the string length of the value associated with field in the hash   |:x:                    |:x:                    |Hashes         |hStrLen        |

## hDel

_**Description**_: Removes a value from the hash stored at key. If the hash table doesn't exist, or the key doesn't exist, FALSE is returned.

##### *Prototype*  

```php
public function hDel(string $key, ...$keys): int {
    return $this->redis->hDel($key, ...$keys);
}
```

##### *Parameters*

- *key*: String(s) | Array. The key(s) to be removed.
- *keys*: String(s) | Array. The key(s) to be removed.

##### *Return value*

*int*: LONG the number of deleted keys, 0 if the key doesn't exist, FALSE if the key isn't a hash.

##### *Example*

```php
$redis->set('key1', 'val1');
$redis->set('key2', 'val2');
$redis->del('key1'); // 1
$redis->del('key1', 'key2'); // 2
$redis->del(['key1', 'key2']); // 2
```

## hExists

_**Description**_: Determine if a hash field exists.

##### *Prototype*  

```php
public function hDel(string $key, ...$keys): int {
    return $this->redis->hDel($key, ...$keys);
}
```

##### *Parameters*

- *key*: String(s) | Array. The key(s) to be removed.
- *keys*: String(s) | Array. The key(s) to be removed.

##### *Return value*

*int*: LONG the number of deleted keys, 0 if the key doesn't exist, FALSE if the key isn't a hash.

##### *Example*

```php
$redis->set('key1', 'val1');
$redis->set('key2', 'val2');
$redis->del('key1'); // 1
$redis->del('key1', 'key2'); // 2
$redis->del(['key1', 'key2']); // 2
```

## hGet

_**Description**_: Get the value of a hash field.

##### *Prototype*  

```php
public function hDel(string $key, ...$keys): int {
    return $this->redis->hDel($key, ...$keys);
}
```

##### *Parameters*

- *key*: String(s) | Array. The key(s) to be removed.
- *keys*: String(s) | Array. The key(s) to be removed.

##### *Return value*

*int*: LONG the number of deleted keys, 0 if the key doesn't exist, FALSE if the key isn't a hash.

##### *Example*

```php
$redis->set('key1', 'val1');
$redis->set('key2', 'val2');
$redis->del('key1'); // 1
$redis->del('key1', 'key2'); // 2
$redis->del(['key1', 'key2']); // 2
```


## hGetAll

_**Description**_: Get all the fields and values in a hash.

##### *Prototype*  

```php
public function hDel(string $key, ...$keys): int {
    return $this->redis->hDel($key, ...$keys);
}
```

##### *Parameters*

- *key*: String(s) | Array. The key(s) to be removed.
- *keys*: String(s) | Array. The key(s) to be removed.

##### *Return value*

*int*: LONG the number of deleted keys, 0 if the key doesn't exist, FALSE if the key isn't a hash.

##### *Example*

```php
$redis->set('key1', 'val1');
$redis->set('key2', 'val2');
$redis->del('key1'); // 1
$redis->del('key1', 'key2'); // 2
$redis->del(['key1', 'key2']); // 2
```


## hIncrBy

_**Description**_: Increment the integer value of a hash field by the given number.

##### *Prototype*  

```php
public function hDel(string $key, ...$keys): int {
    return $this->redis->hDel($key, ...$keys);
}
```

##### *Parameters*

- *key*: String(s) | Array. The key(s) to be removed.
- *keys*: String(s) | Array. The key(s) to be removed.

##### *Return value*

*int*: LONG the number of deleted keys, 0 if the key doesn't exist, FALSE if the key isn't a hash.

##### *Example*

```php
$redis->set('key1', 'val1');
$redis->set('key2', 'val2');
$redis->del('key1'); // 1
$redis->del('key1', 'key2'); // 2
$redis->del(['key1', 'key2']); // 2
```

## hIncrByFloat

_**Description**_: Increment the float value of a hash field by the given amount.

##### *Prototype*  

```php
public function hDel(string $key, ...$keys): int {
    return $this->redis->hDel($key, ...$keys);
}
```

##### *Parameters*

- *key*: String(s) | Array. The key(s) to be removed.
- *keys*: String(s) | Array. The key(s) to be removed.

##### *Return value*

*int*: LONG the number of deleted keys, 0 if the key doesn't exist, FALSE if the key isn't a hash.

##### *Example*

```php
$redis->set('key1', 'val1');
$redis->set('key2', 'val2');
$redis->del('key1'); // 1
$redis->del('key1', 'key2'); // 2
$redis->del(['key1', 'key2']); // 2
```

## hKeys

_**Description**_: Get all the fields in a hash.

##### *Prototype*  

```php
public function hDel(string $key, ...$keys): int {
    return $this->redis->hDel($key, ...$keys);
}
```

##### *Parameters*

- *key*: String(s) | Array. The key(s) to be removed.
- *keys*: String(s) | Array. The key(s) to be removed.

##### *Return value*

*int*: LONG the number of deleted keys, 0 if the key doesn't exist, FALSE if the key isn't a hash.

##### *Example*

```php
$redis->set('key1', 'val1');
$redis->set('key2', 'val2');
$redis->del('key1'); // 1
$redis->del('key1', 'key2'); // 2
$redis->del(['key1', 'key2']); // 2
```

## hLen

_**Description**_: Get the number of fields in a hash.

##### *Prototype*  

```php
public function hDel(string $key, ...$keys): int {
    return $this->redis->hDel($key, ...$keys);
}
```

##### *Parameters*

- *key*: String(s) | Array. The key(s) to be removed.
- *keys*: String(s) | Array. The key(s) to be removed.

##### *Return value*

*int*: LONG the number of deleted keys, 0 if the key doesn't exist, FALSE if the key isn't a hash.

##### *Example*

```php
$redis->set('key1', 'val1');
$redis->set('key2', 'val2');
$redis->del('key1'); // 1
$redis->del('key1', 'key2'); // 2
$redis->del(['key1', 'key2']); // 2
```

## hMGet

_**Description**_: Get the values of all the given hash fields.

##### *Prototype*  

```php
public function hDel(string $key, ...$keys): int {
    return $this->redis->hDel($key, ...$keys);
}
```

##### *Parameters*

- *key*: String(s) | Array. The key(s) to be removed.
- *keys*: String(s) | Array. The key(s) to be removed.

##### *Return value*

*int*: LONG the number of deleted keys, 0 if the key doesn't exist, FALSE if the key isn't a hash.

##### *Example*

```php
$redis->set('key1', 'val1');
$redis->set('key2', 'val2');
$redis->del('key1'); // 1
$redis->del('key1', 'key2'); // 2
$redis->del(['key1', 'key2']); // 2
```

## hMSet

_**Description**_: Set multiple hash fields to multiple values.

##### *Prototype*  

```php
public function hDel(string $key, ...$keys): int {
    return $this->redis->hDel($key, ...$keys);
}
```

##### *Parameters*

- *key*: String(s) | Array. The key(s) to be removed.
- *keys*: String(s) | Array. The key(s) to be removed.

##### *Return value*

*int*: LONG the number of deleted keys, 0 if the key doesn't exist, FALSE if the key isn't a hash.

##### *Example*

```php
$redis->set('key1', 'val1');
$redis->set('key2', 'val2');
$redis->del('key1'); // 1
$redis->del('key1', 'key2'); // 2
$redis->del(['key1', 'key2']); // 2
```

## hSet

_**Description**_: Set the string value of a hash field.

##### *Prototype*  

```php
public function hDel(string $key, ...$keys): int {
    return $this->redis->hDel($key, ...$keys);
}
```

##### *Parameters*

- *key*: String(s) | Array. The key(s) to be removed.
- *keys*: String(s) | Array. The key(s) to be removed.

##### *Return value*

*int*: LONG the number of deleted keys, 0 if the key doesn't exist, FALSE if the key isn't a hash.

##### *Example*

```php
$redis->set('key1', 'val1');
$redis->set('key2', 'val2');
$redis->del('key1'); // 1
$redis->del('key1', 'key2'); // 2
$redis->del(['key1', 'key2']); // 2
```

## hSetNx

_**Description**_: Set the value of a hash field, only if the field does not exist.

##### *Prototype*  

```php
public function hDel(string $key, ...$keys): int {
    return $this->redis->hDel($key, ...$keys);
}
```

##### *Parameters*

- *key*: String(s) | Array. The key(s) to be removed.
- *keys*: String(s) | Array. The key(s) to be removed.

##### *Return value*

*int*: LONG the number of deleted keys, 0 if the key doesn't exist, FALSE if the key isn't a hash.

##### *Example*

```php
$redis->set('key1', 'val1');
$redis->set('key2', 'val2');
$redis->del('key1'); // 1
$redis->del('key1', 'key2'); // 2
$redis->del(['key1', 'key2']); // 2
```

## hVals

_**Description**_: Get all the values in a hash.

##### *Prototype*  

```php
public function hDel(string $key, ...$keys): int {
    return $this->redis->hDel($key, ...$keys);
}
```

##### *Parameters*

- *key*: String(s) | Array. The key(s) to be removed.
- *keys*: String(s) | Array. The key(s) to be removed.

##### *Return value*

*int*: LONG the number of deleted keys, 0 if the key doesn't exist, FALSE if the key isn't a hash.

##### *Example*

```php
$redis->set('key1', 'val1');
$redis->set('key2', 'val2');
$redis->del('key1'); // 1
$redis->del('key1', 'key2'); // 2
$redis->del(['key1', 'key2']); // 2
```

## hScan

_**Description**_: Scan a hash key for members.

##### *Prototype*  

```php
public function hDel(string $key, ...$keys): int {
    return $this->redis->hDel($key, ...$keys);
}
```

##### *Parameters*

- *key*: String(s) | Array. The key(s) to be removed.
- *keys*: String(s) | Array. The key(s) to be removed.

##### *Return value*

*int*: LONG the number of deleted keys, 0 if the key doesn't exist, FALSE if the key isn't a hash.

##### *Example*

```php
$redis->set('key1', 'val1');
$redis->set('key2', 'val2');
$redis->del('key1'); // 1
$redis->del('key1', 'key2'); // 2
$redis->del(['key1', 'key2']); // 2
```

## hStrLen

_**Description**_: Get the string length of the value associated with field in the hash.

##### *Prototype*  

```php
public function hDel(string $key, ...$keys): int {
    return $this->redis->hDel($key, ...$keys);
}
```

##### *Parameters*

- *key*: String(s) | Array. The key(s) to be removed.
- *keys*: String(s) | Array. The key(s) to be removed.

##### *Return value*

*int*: LONG the number of deleted keys, 0 if the key doesn't exist, FALSE if the key isn't a hash.

##### *Example*

```php
$redis->set('key1', 'val1');
$redis->set('key2', 'val2');
$redis->del('key1'); // 1
$redis->del('key1', 'key2'); // 2
$redis->del(['key1', 'key2']); // 2
```
