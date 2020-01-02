### [Redis client for PHP using the PhpRedis C Extension](README.md)

# [Connection](docs/connection.md)

- [connect, open](#connect-open) - Connect to a server
- [pconnect, popen](#pconnect-popen) - Connect to a server (persistent)
- [auth](#auth) - Authenticate to the server
- [select]() - Change the selected database for the current connection
- [swapdb]() - Swaps two Redis databases
- [close]() - Close the connection
- [setOption]() - Set client option
- [getOption]() - Get client option
- [ping]() - Ping the server
- [echo]() - Echo the given string

## Usage

```php
$redis = new Webdcg\Redis\Redis;
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

*Bool*: `TRUE` on success, `FALSE` on error.

##### *Example*

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

*Bool*: `TRUE` on success, `FALSE` on error.

##### *Example*

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

*Bool*: `TRUE` on success, `FALSE` on error.

##### *Example*

```php
$this->redis->auth('secret')
```
