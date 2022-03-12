# Laravel Server Push Middleware

[![tests](https://github.com/kawax/laravel-server-push/actions/workflows/tests.yml/badge.svg)](https://github.com/kawax/laravel-server-push/actions/workflows/tests.yml)
[![Maintainability](https://api.codeclimate.com/v1/badges/8fa23e8f590eb023ac91/maintainability)](https://codeclimate.com/github/kawax/laravel-server-push/maintainability)
[![Test Coverage](https://api.codeclimate.com/v1/badges/8fa23e8f590eb023ac91/test_coverage)](https://codeclimate.com/github/kawax/laravel-server-push/test_coverage)

Remake from
https://github.com/tomschlick/laravel-http2-server-push

## CHANGES
- Support only Laravel mix
- Delete global helpers
- Push only `get` request and `text/html` response
- Compatible with `config/server-push.php`

## Requirements
- PHP >= 7.4
- Laravel >= 6.0

## Installation

```
composer require revolution/laravel-server-push
```

### Publish config file (Optional)
```
php artisan vendor:publish --tag=server-push-config
```

### Add to web middleware group

```php
protected $middlewareGroups = [
        'web' => [
            //...
            \Revolution\ServerPush\ServerPush::class,
        ],
```

## Instead of global helpers

```php
use Revolution\ServerPush\LinkBuilder;

app(LinkBuilder::class)->addLink('/image/test.jpg')->addLink('/css/test.css');
```

## LICENSE
MIT  
Copyright kawax
