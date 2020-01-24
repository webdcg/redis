### [Redis client for PHP using the PhpRedis C Extension](../README.md)
# [Keys](docs/keys.md)

|Command                    |Description                                                                        |Supported              |Tested                 |Class/Trait    |Method     |
|---                        |---                                                                                |:-:                    |:-:                    |---            |---        |
|[del](#del)                |Delete a key [Blocking]                                                            |:white\_check\_mark:   |:white\_check\_mark:   |Keys           |del        |
|[delete](#delete)          |Delete a key [Blocking]                                                            |:white\_check\_mark:   |:white\_check\_mark:   |Keys           |delete     |
|[unlink](#unlink)          |Delete a key [Background]                                                          |:white\_check\_mark:   |:white\_check\_mark:   |Keys           |unlink     |
|[dump](#dump)              |Return a serialized version of the value stored at the specified key.              |:white\_check\_mark:   |:white\_check\_mark:   |Keys           |dump       |
|[exists](#exists)          |Determine if a key exists                                                          |:white\_check\_mark:   |:white\_check\_mark:   |Keys           |exists     |
|[expire](#expire)          |Set a key's time to live in seconds                                                |:white\_check\_mark:   |:white\_check\_mark:   |Keys           |expire     |
|[setTimeout](#setTimeout)  |Set a key's time to live in seconds                                                |:white\_check\_mark:   |:white\_check\_mark:   |Keys           |setTimeout |
|[pexpire](#pexpire)        |Set a key's time to live in seconds                                                |:white\_check\_mark:   |:white\_check\_mark:   |Keys           |pexpire    |
|[expireAt](#expireAt)      |Set the expiration for a key as a UNIX timestamp                                   |:white\_check\_mark:   |:white\_check\_mark:   |Keys           |pexpireAt  |
|[pexpireAt](#pexpireAt)    |Set the expiration for a key as a UNIX timestamp with millisecond precision        |:white\_check\_mark:   |:white\_check\_mark:   |Keys           |pexpireAt  |
|[keys](#keys)              |Find all keys matching the given pattern                                           |:white\_check\_mark:   |:white\_check\_mark:   |Keys           |keys       |
|[getKeys](#getKeys)        |Find all keys matching the given pattern                                           |:white\_check\_mark:   |:white\_check\_mark:   |Keys           |getKeys    |
|[scan](#scan)              | Scan for keys in the keyspace (Redis >= 2.8.0)                                    |:white\_check\_mark:                    |:white\_check\_mark:                    |Keys           |scan    |
|[migrate](#migrate)        | Atomically transfer a key from a Redis instance to another one                    |:white\_check\_mark:                    |:white\_check\_mark:                    |Keys           |migrate    |
|[move](#move)              | Move a key to another database                                                    |:white\_check\_mark:                    |:white\_check\_mark:                    |Keys           |move    |
|[object](#object)          | Inspect the internals of Redis objects                                            |:white\_check\_mark:                    |:white\_check\_mark:                    |Keys           |object    |
|[persist](#persist)        | Remove the expiration from a key                                                  |:white\_check\_mark:                    |:white\_check\_mark:                    |Keys           |persist    |
|[randomKey](#randomKey)    | Return a random key from the keyspace                                             |:white\_check\_mark:                    |:white\_check\_mark:                    |Keys           |randomKey    |
|[rename](#rename)          | Rename a key                                                                      |:white\_check\_mark:                    |:white\_check\_mark:                    |Keys           |rename    |
|[renameKey](#renameKey)    | Rename a key                                                                      |:white\_check\_mark:                    |:white\_check\_mark:                    |Keys           |renameKey    |
|[renameNx](#renameNx)      | Rename a key, only if the new key does not exist                                  |:white\_check\_mark:                    |:white\_check\_mark:                    |Keys           |renameNx    |
|[type](#type)              | Determine the type stored at key                                                  |:x:                    |:x:                    |Keys           |type    |
|[sort](#sort)              | Sort the elements in a list, set or sorted set                                    |:x:                    |:x:                    |Keys           |sort    |
|[ttl](#ttl)                | Get the time to live for a key                                                    |:white\_check\_mark:                    |:white\_check\_mark:                    |Keys           |ttl    |
|[pttl](#pttl)              | Get the time to live for a key                                                    |:white\_check\_mark:                    |:white\_check\_mark:                    |Keys           |pttl    |
|[restore](#restore)        | Create a key using the provided serialized value, previously obtained with dump.  |:x:                    |:x:                    |Keys           |restore    |

## del

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
```

## delete

_**Description**_: Remove specified keys [Non Blocking].  

##### *Prototype*  

```php
public function delete(...$keys): int {
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
public function expireAt(string $key, int $ttl): bool {
    return $this->redis->expireAt($key, $ttl);
}
```

##### *Parameters*

- *key*: string. The key that will disappear.
- *ttl*: integer. The key's remaining Time To Live, in seconds.

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

## pexpireAt

_**Description**_: Sets an expiration date (a timestamp) on an item in milliseconds.

##### *Prototype*  

```php
public function pexpireAt(string $key, int $ttl): bool {
    return $this->redis->pexpireAt($key, $ttl);
}
```

##### *Parameters*

- *key*: string. The key that will disappear.
- *ttl*: integer. The key's remaining Time To Live, in milliseconds.

##### *Return value*

*bool*: true in case of success, false in case of failure.

##### *Example*

```php
[$usec, $sec] = explode(' ', microtime());
$now = floor(((float) $usec + (float) $sec) * 1000);
$redis->set('key', 'value');        
$redis->pexpireAt('key', $now + 10);  // key will disappear in 10  milliseconds
usleep(20 * 1000);                    // wait 20 milliseconds
$redis->exists('key');               // 0
```

## keys

_**Description**_: Find all keys matching the given pattern.

##### *Prototype*  

```php
public function keys(string $pattern): array {
    return $this->redis->keys($pattern);
}
```

##### *Parameters*

- *pattern*: string. Pattern to match, using '\*' as a wildcard.

##### *Return value*

*array*: Array of string: The keys that match a certain pattern.

##### *Example*

```php
$redis->set('key1', 'value');
$redis->set('key2', 'value');
$redis->set('key3', 'value');
$redis->keys('key*');
/*
array(3) {
  [0] => string(4) "key3"
  [1] => string(4) "key1"
  [2] => string(4) "key2"
}
*/
```

## getKeys

_**Description**_: Find all keys matching the given pattern.

Note: getKeys is an alias for keys and will be removed in future versions of phpredis.

##### *Prototype*  

```php
public function getKeys(string $pattern): array {
    return $this->redis->keys($pattern);
}
```

##### *Parameters*

- *pattern*: string. Pattern to match, using '\*' as a wildcard.

##### *Return value*

*array*: Array of string: The keys that match a certain pattern.

##### *Example*

```php
$redis->set('key1', 'value');
$redis->set('key2', 'value');
$redis->set('key3', 'value');
$redis->getKeys('key*');
/*
array(3) {
  [0] => string(4) "key3"
  [1] => string(4) "key1"
  [2] => string(4) "key2"
}
*/
```

## scan

_**Description**_: Scan the keyspace for keys.

##### *Prototype*  

```php
public function scan($iterator = null, string $pattern = '*', int $count = 10) {
    return $this->redis->scan($iterator, $pattern, $count);
}
```

##### *Parameters*

- *iterator*: String. LONG (reference): Iterator, initialized to NULL.
- *pattern*: String. Pattern to match, using '\*' as a wildcard.
- *count*: Integer. LONG, Optional: Count of keys per iteration (only a suggestion to Redis).

##### *Return value*

*array*: Array, boolean: This function will return an array of keys or FALSE if Redis returned zero keys.

##### *Example*

```php
/* Without enabling Redis::SCAN_RETRY (default condition) */
$it = NULL;
do {
    // Scan for some keys
    $arr_keys = $redis->scan($it);

    // Redis may return empty results, so protect against that
    if ($arr_keys !== FALSE) {
        foreach($arr_keys as $str_key) {
            echo "Here is a key: $str_key\n";
        }
    }
} while ($it > 0);
echo "No more keys to scan!\n";

/* With Redis::SCAN_RETRY enabled */
$redis->setOption(Redis::OPT_SCAN, Redis::SCAN_RETRY);
$it = NULL;

/* phpredis will retry the SCAN command if empty results are returned from the
   server, so no empty results check is required. */
while ($arr_keys = $redis->scan($it)) {
    foreach ($arr_keys as $str_key) {
        echo "Here is a key: $str_key\n";
    }
}
echo "No more keys to scan!\n";
```

## migrate

_**Description**_: Migrates a key to a different Redis instance.

Note:: Redis introduced migrating multiple keys in 3.0.6, so you must have at least that version in order to call migrate with an array of keys.

##### *Prototype*  

```php
public function migrate(
    string $host,
    int $port,
    array $keys,
    int $db,
    int $timeout,
    ?bool $copy = false,
    ?bool $replace = false
) : bool {
    return $this->redis->migrate($host, $port, $keys, $db, $timeout);
}
```

##### *Parameters*

- *host*: String. The destination host.
- *port*: Integer. The TCP port to connect to.
- *keys*: Array. Key(s) to be moved.
- *db*: Integer. The target DB.
- *timeout*: Integer. The maximum amount of time given to this transfer.
- *copy*: Boolean. (optional) Should we send the COPY flag to redis.
- *replace*: Boolean. (optional) Should we send the REPLACE flag to redis.

##### *Return value*

*array*: Array of string: The keys that match a certain pattern.

##### *Example*

```php
$redis->migrate('backup', 6379, 'foo', 0, 3600);
$redis->migrate('backup', 6379, 'foo', 0, 3600, true, true); /* copy and replace */
$redis->migrate('backup', 6379, 'foo', 0, 3600, false, true); /* just REPLACE flag */

/* Migrate multiple keys (requires Redis >= 3.0.6)
$redis->migrate('backup', 6379, ['key1', 'key2', 'key3'], 0, 3600);
```

## move

_**Description**_: Moves a key to a different database.

##### *Prototype*  

```php
public function move(string $key, int $db): bool {
    return $this->redis->move($key, $db);
}
```

##### *Parameters*

- *key*: String. the key to move.
- *db*: Integer. dbindex, the database number to move the key to.

##### *Return value*

*bool*: true in case of success, false in case of failure.

##### *Example*

```php
$redis->select(0);      // switch to DB 0
$redis->set('x', '42'); // write 42 to x
$redis->move('x', 1);   // move to DB 1
$redis->select(1);      // switch to DB 1
$redis->get('x');       // will return 42
```
