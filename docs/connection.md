### [Redis client for PHP using the PhpRedis C Extension](../README.md)

# [Connection](docs/connection.md)

|Command                    |Description                                                |Supported              |Tested                 |Class/Trait    |Method     |
|---                        |---                                                        |:-:                    |:-:                    |---            |---        |
|[connect](#connect)        |Connect to a server                                        |:white\_check\_mark:   |:white\_check\_mark:   |Strings        |connect    |
|[open](#open)              |Connect to a server                                        |:white\_check\_mark:   |:white\_check\_mark:   |Strings        |open       |
|[pconnect](#pconnect)      |Connect to a server (persistent)                           |:white\_check\_mark:   |:white\_check\_mark:   |Strings        |pconnect   |
|[popen](#popen)            |Connect to a server (persistent)                           |:white\_check\_mark:   |:white\_check\_mark:   |Strings        |popen      |
|[auth](#auth)              |Authenticate to the server                                 |:white\_check\_mark:   |:white\_check\_mark:   |Strings        |auth       |
|[select](#select)          |Change the selected database for the current connection    |:white\_check\_mark:   |:white\_check\_mark:   |Strings        |select     |
|[swapdb](#swapdb)          |Swaps two Redis databases                                  |:white\_check\_mark:   |:white\_check\_mark:   |Strings        |swapdb     |
|[close](#close)            |Close the connection                                       |:white\_check\_mark:   |:white\_check\_mark:   |Strings        |close      |
|[setOption](#setOption)    |Set client option                                          |:white\_check\_mark:   |:white\_check\_mark:   |Strings        |setOption  |
|[getOption](#getOption)    |Get client option                                          |:white\_check\_mark:   |:white\_check\_mark:   |Strings        |getOption  |
|[ping](#ping)              |Ping the server                                            |:white\_check\_mark:   |:white\_check\_mark:   |Strings        |ping       |
|[echo](#echo)              |Echo the given string                                      |:white\_check\_mark:   |:white\_check\_mark:   |Strings        |echo       |

## Usage

```php
$redis = new Webdcg\Redis\Redis;

$redis->connect('127.0.0.1', 6379);
$redis->auth('secret');
$redis->select(1);
$redis->swapdb(0, 1);
$redis->close();
$redis->setOption(\Redis::OPT_PREFIX, 'redis:');
$redis->getOption(\Redis::OPT_PREFIX)
$redis->ping('pong');
$redis->echo('redis');
```

## connect, open

_**Description**_: Connects to a Redis instance.

##### *Prototype*  

```php
public function connect(
    string $host = '127.0.0.1', 
    int $port = 6379, 
    float $timeout = 0.0, 
    $reserved = null, 
    int $retry_interval = 0, 
    float $read_timeout = 0
) : bool
```

##### *Parameters*

- *host*: String. can be a host, or the path to a unix domain socket. Starting from version 5.0.0 it is 
            possible to specify schema 
- *port*: Integer. (optional)  
- *timeout*: Float. value in seconds (optional, default is 0 meaning unlimited)  
- *reserved*: should be NULL if retry_interval is specified  
- *retry_interval*: Integer. value in milliseconds (optional)  
- *read_timeout*: Float. value in seconds (optional, default is 0 meaning unlimited)  

##### *Return value*

*bool*: `true` on success, `false` on error.

##### *Examples*

```php
$redis->connect('127.0.0.1'); // port 6379 by default
$redis->connect('127.0.0.1', 6379);
$redis->connect('tls://127.0.0.1', 6379); // enable transport level security.
$redis->connect('tls://127.0.0.1'); // enable transport level security, port 6379 by default.
$redis->connect('127.0.0.1', 6379, 2.5); // 2.5 sec timeout.
$redis->connect('/tmp/redis.sock'); // unix domain socket.
$redis->connect('127.0.0.1', 6379, 1, NULL, 100); // 1 sec timeout, 100ms delay between reconnection attempts.
$redis->connect('unix://redis.sock'); // relative path to unix domain socket requires version 5.0.0 or higher.
```

## pconnect, popen

_**Description**_: Connects to a Redis instance or reuse a connection already established with `pconnect`/`popen`.

The connection will not be closed on end of request until the php process ends. So be prepared for too many
open FD's errors (specially on redis server side) when using persistent connections on many servers
connecting to one redis server.

Also more than one persistent connection can be made identified by either host + port + timeout or
host + persistent_id or unix socket + timeout.

Starting from version 4.2.1, it became possible to use connection pooling by setting INI variable
`redis.pconnect.pooling_enabled` to 1.

This feature is not available in threaded versions. pconnect and popen then working like their non
persistent equivalents.

##### *Prototype*  

```php
public function pconnect(
    string $host = '127.0.0.1', 
    int $port = 6379, 
    float $timeout = 0.0, 
    string $persistent_id,
    ?int $retry_interval = 0,
    ?float $read_timeout = 0
) : bool
```

##### *Parameters*

- *host*: String. can be a host, or the path to a unix domain socket. Starting from version 5.0.0 it is 
            possible to specify schema 
- *port*: Integer. (optional)  
- *timeout*: Float. value in seconds (optional, default is 0 meaning unlimited)  
- *persistent_id*: String. identity for the requested persistent connection
- *retry_interval*: Integer. value in milliseconds (optional)  
- *read_timeout*: Float. value in seconds (optional, default is 0 meaning unlimited)  

##### *Return value*

*bool*: `true` on success, `false` on error.

##### *Examples*

```php
$redis->pconnect('127.0.0.1'); // port 6379 by default
$redis->pconnect('127.0.0.1', 6379);
$redis->pconnect('tls://127.0.0.1', 6379); // enable transport level security.
$redis->pconnect('tls://127.0.0.1'); // enable transport level security, port 6379 by default.
$redis->pconnect('127.0.0.1', 6379, 2.5); // 2.5 sec timeout.
$redis->pconnect('/tmp/redis.sock'); // unix domain socket.
$redis->pconnect('127.0.0.1', 6379, 1, NULL, 100); // 1 sec timeout, 100ms delay between reconnection attempts.
$redis->pconnect('unix://redis.sock'); // relative path to unix domain socket requires version 5.0.0 or higher.
```

## auth

_**Description**_: Authenticate the connection using a password. Warning: The password is sent in plain-text over the network.

##### *Prototype*  

```php
public function auth(string $password) : bool {
    return $this->redis->auth($password);
}
```

##### *Parameters*

- *password*: String.

##### *Return value*

*bool*: `true` on success, `false` on error.

##### *Example*

```php
$redis->auth('secret')
```

## select

_**Description**_: Change the selected database for the current connection.

##### *Prototype*  

```php
public function select(int $db) : bool {
    return $this->redis->select($db);
}
```

##### *Parameters*

- *db*: Integer. the database number to switch to (valid 0 - 15)

##### *Return value*

*bool*: `true` on success, `false` on error.

##### *Example*

```php
$db = 0; // Valid DBs 0 - 15
$redis->select($db)
```

## swapdb

_**Description**_: Swap one Redis database with another atomically.

##### *Prototype*  

```php
public function swapdb(int $db1, int $db2) : bool {
    return $this->redis->swapdb($db1, $db2);
}
```

##### *Parameters*

- *db1*: Integer. Database to Switch From (valid 0 - 15)
- *db2*: Integer. Database to Switch To  (valid 0 - 15)

##### *Return value*

*bool*: `true` on success, `false` on error.

##### *Example*

```php
$dbFrom = 0;
$dbTo = 1;
$redis->swapdb($dbFrom, $dbTo)
```

## close

_**Description**_: Disconnects from the Redis instance.

##### *Prototype*  

```php
public function close() : bool {
    return $this->redis->close();
}
```

##### *Parameters*

- None.

##### *Return value*

*bool*: `true` on success, `false` on error.

##### *Example*

```php
$redis->close();
```

## setOption

_**Description**_: Set a client option.

##### *Prototype*  

```php
public function setOption(string $name, string $value) : bool {
    return $this->redis->setOption($name, $value);
}
```

##### *Parameters*

- *name*: String. Option Name
- *value*: String. Option Value

##### *Return value*

*bool*: `true` on success, `false` on error.

##### *Example*

```php
$redis->setOption(Redis::OPT_SERIALIZER, Redis::SERIALIZER_NONE);     // Don't serialize data
$redis->setOption(Redis::OPT_SERIALIZER, Redis::SERIALIZER_PHP);      // Use built-in serialize/unserialize
$redis->setOption(Redis::OPT_SERIALIZER, Redis::SERIALIZER_IGBINARY); // Use igBinary serialize/unserialize
$redis->setOption(Redis::OPT_SERIALIZER, Redis::SERIALIZER_MSGPACK);  // Use msgpack serialize/unserialize

$redis->setOption(Redis::OPT_PREFIX, 'myAppName:'); // use custom prefix on all keys

/* Options for the SCAN family of commands, indicating whether to abstract
   empty results from the user.  If set to SCAN_NORETRY (the default), phpredis
   will just issue one SCAN command at a time, sometimes returning an empty
   array of results.  If set to SCAN_RETRY, phpredis will retry the scan command
   until keys come back OR Redis returns an iterator of zero
*/
$redis->setOption(Redis::OPT_SCAN, Redis::SCAN_NORETRY);
$redis->setOption(Redis::OPT_SCAN, Redis::SCAN_RETRY);
```

## getOption

_**Description**_: Get a client's option value.

##### *Prototype*  

```php
public function getOption(string $name) {
    return $this->redis->getOption($name);
}
```

##### *Parameters*

- *name*: String. Option Name

##### *Return value*

*string*: Option value.

##### *Example*

```php
// return Redis::SERIALIZER_NONE, Redis::SERIALIZER_PHP, 
//        Redis::SERIALIZER_IGBINARY, or Redis::SERIALIZER_MSGPACK
$redis->getOption(Redis::OPT_SERIALIZER);
```

## ping

_**Description**_: Check the current connection status.  
Note: Prior to PhpRedis 5.0.0 this command simply returned the string +PONG.

##### *Prototype*  

```php
public function ping(?string $message = null) {
    return $message ? $this->redis->ping($message) : $this->redis->ping();
}
```

##### *Parameters*

- *name*: String. Option Name 

##### *Return value*

*mixed*: `true` on success, , or the passed `string` if called with an argument.

##### *Example*

```php
/* When called without an argument, ping returns `true` */
$redis->ping();

/* If passed an argument, that argument is returned.  Here 'redis' will be returned */
$redis->ping('redis');
```

## echo

_**Description**_: Sends a string to Redis, which replies with the same string.

##### *Prototype*  

```php
public function echo(string $message): string {
    return $this->redis->echo($message);
}
```

##### *Parameters*

- *message*: String. The message to send 

##### *Return value*

*string*: The same message sent.

##### *Example*

```php
$redis->echo('redis'); // returns redis
```
