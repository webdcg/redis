# Redis client for PHP using the PhpRedis C Extension

[![StyleCI](https://github.styleci.io/repos/217066042/shield)](https://github.styleci.io/repos/217066042/shield)
[![Latest Version on Packagist](https://img.shields.io/packagist/v/webdcg/redis.svg?style=flat-square)](https://packagist.org/packages/webdcg/redis)
[![Build Status](https://img.shields.io/travis/webdcg/redis/master.svg?style=flat-square)](https://travis-ci.org/webdcg/redis)
[![Quality Score](https://img.shields.io/scrutinizer/g/webdcg/redis.svg?style=flat-square)](https://scrutinizer-ci.com/g/webdcg/redis)
[![Total Downloads](https://img.shields.io/packagist/dt/webdcg/redis.svg?style=flat-square)](https://packagist.org/packages/webdcg/redis)

This is where your description should go. Try and limit it to a paragraph or two, and maybe throw in a mention of what PSRs you support to avoid any confusion with users and contributors.

## Installation

You can install the package via composer:

```bash
composer require webdcg/redis
```

## Usage

``` php
$redis = new Webdcg\Redis\Redis;
```

### Connection

```
$redis->connect('127.0.0.1', 6379);
$redis->auth('secret');
$redis->select(1);
$redis->swapdb(0, 1);
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

If you discover any security related issues, please email roberto.luna.rojas@gmail.com instead of using the issue tracker.

## Credits

- [Roberto Luna Rojas](https://github.com/webdcg)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## PHP Package Boilerplate

This package was generated using the [PHP Package Boilerplate](https://laravelpackageboilerplate.com).