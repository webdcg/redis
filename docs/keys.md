### [Redis client for PHP using the PhpRedis C Extension](../README.md)

# [Keys](docs/keys.md)

- [del, delete](#del-delete) - Delete a key [Blocking]
- [unlink](#unlink) - Delete a key [Background]
- [dump](#dump) - Return a serialized version of the value stored at the specified key.
- [exists](#exists) - Determine if a key exists
- [expire](#expire) - Set a key's time to live in seconds
- [setTimeout](#setTimeout) - Set a key's time to live in seconds
- [pexpire](#pexpire) - Set a key's time to live in seconds
- [expireAt, pexpireAt](#expireAt-pexpireAt) - Set the expiration for a key as a UNIX timestamp
- [keys, getKeys](#keys-getKeys) - Find all keys matching the given pattern
- [scan](#scan) - Scan for keys in the keyspace (Redis >= 2.8.0)
- [migrate](#migrate) - Atomically transfer a key from a Redis instance to another one
- [move](#move) - Move a key to another database
- [object](#object) - Inspect the internals of Redis objects
- [persist](#persist) - Remove the expiration from a key
- [randomKey](#randomKey) - Return a random key from the keyspace
- [rename, renameKey](#rename-renameKey) - Rename a key
- [renameNx](#renameNx) - Rename a key, only if the new key does not exist
- [type](#type) - Determine the type stored at key
- [sort](#sort) - Sort the elements in a list, set or sorted set
- [ttl, pttl](#ttl-pttl) - Get the time to live for a key
- [restore](#restore) - Create a key using the provided serialized value, previously obtained with dump.

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