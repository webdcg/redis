# Redis client for PHP using the [PhpRedis](https://github.com/phpredis/phpredis) C Extension

[![StyleCI](https://github.styleci.io/repos/217066042/shield)](https://github.styleci.io/repos/217066042/shield)
[![Latest Version on Packagist](https://img.shields.io/packagist/v/webdcg/redis.svg?style=flat-square)](https://packagist.org/packages/webdcg/redis)
[![Build Status](https://img.shields.io/travis/webdcg/redis/master.svg?style=flat-square)](https://travis-ci.org/webdcg/redis)
[![Quality Score](https://img.shields.io/scrutinizer/g/webdcg/redis.svg?style=flat-square)](https://scrutinizer-ci.com/g/webdcg/redis)
[![Total Downloads](https://img.shields.io/packagist/dt/webdcg/redis.svg?style=flat-square)](https://packagist.org/packages/webdcg/redis)

OOP Redis client for PHP using the [PhpRedis](https://github.com/phpredis/phpredis) C Extension

Table of contents
----

1. [Installation](#installation)
2. [Classes and methods](#classes-and-methods)
    - [Usage](#usage)
    - [Bits](#bits)
    - [Connection](#connection)
    - [Geocoding](#Geocoding)
    - [Hashes](#hashes)
    - [HyperLogLogs](#HyperLogLogs)
    - [Introspection](#introspection)
    - [Keys](#keys)
    - [Lists](#lists)
    - [Pub/sub](#pubsub)
    - [Scripting](#scripting)
    - [Sets](#sets)
    - [Sorted sets](#sorted-sets)
    - [Streams](#Streams)
    - [Strings](#strings)
    - [Transactions](#transactions)

## Installation

You can install the package via composer:

```bash
composer require webdcg/redis
```

## Classes and methods

## Usage

```php
$redis = new Webdcg\Redis\Redis;
```

### [Bits](docs/bits.md)

```php
// Count set bits in a string
$redis->bitCount('key');
$redis->bitField('key');
$redis->bitPos('key');
$redis->bitOp('key');
$redis->getBit('key');
$redis->setBit('key');
```

### [Connection](docs/connection.md)

```php
$redis->connect('127.0.0.1', 6379);
$redis->open('127.0.0.1', 6379);
$redis->pconnect('127.0.0.1', 6379);
$redis->popen('127.0.0.1', 6379);
$redis->auth('secret');
$redis->select(1);
$redis->swapdb(0, 1);
$redis->close();
$redis->setOption(\Redis::OPT_PREFIX, 'redis:');
$redis->getOption(\Redis::OPT_PREFIX)
$redis->ping('pong');
$redis->echo('redis');
```

### [Geocoding](docs/geocoding.md)

```php
$redis->geoAdd('Geocoding', -122.431, 37.773, 'San Francisco');
$redis->geoHash('Geocoding', 'San Francisco');
```

### [Hashes](docs/hashes.md)

```php
$redis->del('key');
$redis->delete('key');
$redis->unlink('key');
```

### [HyperLogLogs](docs/hyperloglogs.md)

```php
$redis->pfAdd('HyperLogLog', ['a', 'b', 'c']);
$redis->pfCount('HyperLogLog'); // 3
$redis->pfAdd('HyperLogLog2', ['b', 'd']);
$redis->pfMerge('HyperLogLogMerged', ['HyperLogLog', 'HyperLogLog2']);
$redis->pfCount('HyperLogLogMerged'); // 4 => a, b, c, d
```

### [Introspection](docs/introspection.md)

```php
$redis->del('key');
$redis->delete('key');
$redis->unlink('key');
```

### [Keys](docs/keys.md)

```php
$redis->del('key');
$redis->delete('key');
$redis->unlink('key');
```

### [Lists](docs/lists.md)

```php
$redis->del('key');
$redis->delete('key');
$redis->unlink('key');
```

### [Pub/sub](docs/pubsub.md)

```php
$redis->del('key');
$redis->delete('key');
$redis->unlink('key');
```

### [Scripting](docs/scripting.md)

```php
$redis->del('key');
$redis->delete('key');
$redis->unlink('key');
```

### [Sets](docs/sets.md)

```php
$redis->del('key');
$redis->delete('key');
$redis->unlink('key');
```

### [Sorted Sets](docs/sorted-sets.md)

```php
$redis->del('key');
$redis->delete('key');
$redis->unlink('key');
```

### [Streams](docs/streams.md)

```php
$redis->del('key');
$redis->delete('key');
$redis->unlink('key');
```

### [Strings](docs/strings.md)

```php
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

### [Transactions](docs/transactions.md)

```php
$redis->del('key');
$redis->delete('key');
$redis->unlink('key');
```

### Testing

``` bash
composer test
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email rluna@webdcg.com instead of using the issue tracker.

## Credits

- [Roberto Luna Rojas](https://github.com/webdcg)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## PHP Package Boilerplate

This package was generated using the [PHP Package Boilerplate](https://laravelpackageboilerplate.com).