### Redis client for PHP using the PhpRedis C Extension

# [Connection](docs/connection.md)

- [connect, open](#connect-open) - Connect to a server
- [pconnect, popen]() - Connect to a server (persistent)
- [auth]() - Authenticate to the server
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
    float $timeout = 0, 
    $reserved = null, 
    int $retry_interval = 0, 
    float $read_timeout = 0
) : bool
```

##### *Parameters*

- *host*: string. can be a host, or the path to a unix domain socket. Starting from version 5.0.0 it is possible to specify schema 
- *port*: int, optional  
- *timeout*: float, value in seconds (optional, default is 0 meaning unlimited)  
- *reserved*: should be NULL if retry_interval is specified  
- *retry_interval*: int, value in milliseconds (optional)  
- *read_timeout*: float, value in seconds (optional, default is 0 meaning unlimited)

##### *Return value*

*BOOL*: `TRUE` on success, `FALSE` on error.

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