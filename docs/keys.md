### [Redis client for PHP using the PhpRedis C Extension](../README.md)

# [Keys](docs/keys.md)

|Command                    |Description                                                            |Supported              |Tested                 |Class/Trait    |Method     |
|---                        |---                                                                    |---                    |---                    |---            |---        |
|[del](#del)                |Delete a key [Blocking]                                                |:white\_check\_mark:   |:white\_check\_mark:   |Keys           |del        |
|[delete](#delete)          |Delete a key [Blocking]                                                |:white\_check\_mark:   |:white\_check\_mark:   |Keys           |delete     |
|[unlink](#unlink)          |Delete a key [Background]                                              |:white\_check\_mark:   |:white\_check\_mark:   |Keys           |unlink     |
|[dump](#dump)              |Return a serialized version of the value stored at the specified key.  |:white\_check\_mark:   |:white\_check\_mark:   |Keys           |dump       |
|[exists](#exists) | Determine if a key exists | :x: | :x: | Keys | del |
|[expire](#expire) | Set a key's time to live in seconds | :x: | :x: | Keys | del |
|[setTimeout](#setTimeout) | Set a key's time to live in seconds | :x: | :x: | Keys | del |
|[pexpire](#pexpire) | Set a key's time to live in seconds | :x: | :x: | Keys | del |
|[expireAt](#expireAt) | Set the expiration for a key as a UNIX timestamp | :x: | :x: | Keys | del |
|[pexpireAt](#pexpireAt) | Set the expiration for a key as a UNIX timestamp with millisecond precision | :x: | :x: | Keys | del |
|[keys, getKeys](#keys-getKeys) | Find all keys matching the given pattern | :x: | :x: | Keys | del |
|[scan](#scan) | Scan for keys in the keyspace (Redis >= 2.8.0) | :x: | :x: | Keys | del |
|[migrate](#migrate) | Atomically transfer a key from a Redis instance to another one | :x: | :x: | Keys | del |
|[move](#move) | Move a key to another database | :x: | :x: | Keys | del |
|[object](#object) | Inspect the internals of Redis objects | :x: | :x: | Keys | del |
|[persist](#persist) | Remove the expiration from a key | :x: | :x: | Keys | del |
|[randomKey](#randomKey) | Return a random key from the keyspace | :x: | :x: | Keys | del |
|[rename, renameKey](#rename-renameKey) | Rename a key | :x: | :x: | Keys | del |
|[renameNx](#renameNx) | Rename a key, only if the new key does not exist | :x: | :x: | Keys | del |
|[type](#type) | Determine the type stored at key | :x: | :x: | Keys | del |
|[sort](#sort) | Sort the elements in a list, set or sorted set | :x: | :x: | Keys | del |
|[ttl, pttl](#ttl-pttl) | Get the time to live for a key | :x: | :x: | Keys | del |
|[restore](#restore) | Create a key using the provided serialized value, previously obtained with dump. | :x: | :x: | Keys | del |

## del, delete

_**Description**_: Remove specified keys [Blocking].  
Note: Should be avoided, *unlink* is recommended.

##### *Prototype*  

```php
public function del(...$keys): int {
    return $this->redis->del(...$keys);
}
```

##### *Parameters*

- *keys*: String(s) | Array. The key(s) to be removed.

##### *Return value*

*int*: Number of keys deleted.

##### *Example*

```php
$redis->set('key1', 'val1');
$redis->set('key2', 'val2');
$redis->del('key1'); // 1
$redis->del('key1', 'key2'); // 2
$redis->del(['key1', 'key2']); // 2

$redis->set('key1', 'val1');
$redis->set('key2', 'val2');
$redis->delete('key1'); // 1
$redis->delete('key1', 'key2'); // 2
$redis->delete(['key1', 'key2']); // 2
```

## unlink

_**Description**_: Remove specified keys [Non Blocking].  
 Note: If you are connecting to Redis server >= 4.0.0 you can remove a key with the unlink method in the exact same way you would use del.  
 The Redis unlink command is non-blocking and will perform the actual deletion asynchronously.

##### *Prototype*  

```php
public function unlink(...$keys): int {
    return $this->redis->unlink(...$keys);
}
```

##### *Parameters*

- *keys*: String(s) | Array. The key(s) to be removed.

##### *Return value*

*int*: Number of keys deleted.

##### *Example*

```php
$redis->set('key1', 'val1');
$redis->set('key2', 'val2');
$redis->unlink('key1'); // 1
$redis->unlink('key1', 'key2'); // 2
$redis->unlink(['key1', 'key2']); // 2
```

## dump

_**Description**_: Return a serialized version of the value stored at the specified key.

##### *Prototype*  

```php
public function dump(string $key): string {
    return $this->redis->dump($key);
}
```

##### *Parameters*

- *key*: String

##### *Return value*

*string*: The Redis encoded value of the key, or FALSE if the key doesn't exist.

##### *Example*

```php
$redis->set('string', 'value')
$redis->dump('string'); // value
$redis->dump('nonexisting'); // false
```

## exists

_**Description**_: Determine if a key exists.

##### *Prototype*  

```php
public function exists(...$keys): int {
    return $this->redis->exists(...$keys);
}
```

##### *Parameters*

- *keys*: String(s) | Array. The key(s) to be removed.

##### *Return value*

*int*: The number of keys tested that do exist.

##### *Example*

```php
$redis->set('key', 'value');
$redis->exists('key'); // 1
$redis->exists('NonExistingKey'); // 0

$redis->set('key1', 'value');
$redis->set('key2', 'value');
$redis->exists(['key1', 'key2']); // 2
$redis->exists(['key1', 'NonExistingKey']); // 1
```

## expire

_**Description**_: Set a key's time to live in seconds.

##### *Prototype*  

```php
public function expire(string $key, int $ttl): bool {
    return $this->redis->expire($key, $ttl);
}
```

##### *Parameters*

- *key*: string. The key that will disappear.
- *ttl*: integer. The key's remaining Time To Live, in seconds.

##### *Return value*

*bool*: true in case of success, false in case of failure.

##### *Example*

```php
$redis->set('key', 'value');
$redis->expire('key', 1);   // x will disappear in 1 seconds.
sleep(2);                   // wait 5 seconds
$redis->exists('key');      // 0
```

## setTimeout

_**Description**_: Set a key's time to live in seconds.

##### *Prototype*  

```php
public function setTimeout(string $key, int $ttl): bool {
    return $this->redis->expire($key, $ttl);
}
```

##### *Parameters*

- *key*: string. The key that will disappear.
- *ttl*: integer. The key's remaining Time To Live, in seconds.

##### *Return value*

*bool*: true in case of success, false in case of failure.

##### *Example*

```php
$redis->set('key', 'value');
$redis->expire('key', 1);   // x will disappear in 1 seconds.
sleep(2);                   // wait 5 seconds
$redis->exists('key');      // 0
```

**Note**: setTimeout is an alias for expire and will be removed in future versions of phpredis.

## pexpire

_**Description**_: Set a key's time to live in milliseconds.

##### *Prototype*  

```php
public function pexpire(string $key, int $ttl): bool {
    return $this->redis->pexpire($key, $ttl);
}
```

##### *Parameters*

- *key*: string. The key that will disappear.
- *ttl*: integer. The key's remaining Time To Live, in milliseconds.

##### *Return value*

*bool*: true in case of success, false in case of failure.

##### *Example*

```php
$redis->set('key', 'value');
$redis->pexpire('key', 100);    // x will disappear in 1 seconds.
usleep(110 * 1000);             // wait 110 milliseconds
$redis->exists('key');          // 0
```

## expireAt

_**Description**_: Set the expiration for a key as a UNIX timestamp.

##### *Prototype*  

```php
public function pexpire(string $key, int $ttl): bool {
    return $this->redis->pexpire($key, $ttl);
}
```

##### *Parameters*

- *key*: string. The key that will disappear.
- *ttl*: integer. The key's remaining Time To Live, in milliseconds.

##### *Return value*

*bool*: true in case of success, false in case of failure.

##### *Example*

```php
$now = time();                      // Current Timestamp
$redis->set('key', 'value');        
$redis->expireAt('key', $now + 1);  // key will disappear in 1 second
sleep(1);                           // wait 1 second
$redis->exists('key');              // 0
```
