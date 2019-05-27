# Laravel Server Push Middleware

[![Build Status](https://travis-ci.com/kawax/laravel-server-push.svg?branch=master)](https://travis-ci.com/kawax/laravel-server-push)
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
- PHP >= 7.1
- Laravel >= 5.5

## Installation

```
composer require revolution/laravel-server-push
```

### Publish config file
```
php artisan vendor:publish --provider="Revolution\ServerPush\Providers\ServerPushServiceProvider"
```

### Add to web middleware group

```php
protected $middlewareGroups = [
        'web' => [
            //...
            \Revolution\ServerPush\ServerPush::class,
        ],
```


## LICENSE
MIT  
Copyright kawax
