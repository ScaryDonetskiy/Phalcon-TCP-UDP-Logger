# TCP Logger Adapter for Phalcon PHP #

[![Packagist](https://img.shields.io/packagist/l/vados/phalcon-tcplogger.svg)]()
[![PHP from Packagist](https://img.shields.io/packagist/php-v/vados/phalcon-tcplogger.svg)]()
[![Packagist](https://img.shields.io/packagist/dt/vados/phalcon-tcplogger.svg)]()
[![Bitbucket issues](https://img.shields.io/bitbucket/issues/Scary_Donetskiy/phalcon-tcplogger.svg)]()

Works with PHP 7.1+

### Usage ###

Adapter implement \Phalcon\Logger\AdapterInterface. You can use it like an any other adapter from Phalcon Framework

```php
$logger = new \Vados\TCPLogger\Adapter('127.0.0.1', 8080);
$logger->error('Error message');
```

### Example ###

You can find examples of usage in [official documentation](https://docs.phalconphp.com/en/3.2/logging) for Phalcon

### Installation ###

Use composer for installation
```bash
composer require vados/phalcon-tcplogger
```

### Contribution guidelines ###

* Writing tests
* Code review
* Guidelines accord