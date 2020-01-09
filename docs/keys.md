### [Redis client for PHP using the PhpRedis C Extension](../README.md)

# [Keys](docs/keys.md)

- [del, delete](#del-delete) - Delete a key [Blocking]
- [unlink](#unlink) - Delete a key [Background]
- [dump](#dump) - Return a serialized version of the value stored at the specified key.
- [exists](#exists) - Determine if a key exists
- [expire, setTimeout, pexpire](#expire-setTimeout-pexpire) - Set a key's time to live in seconds
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
