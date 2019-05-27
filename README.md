# Laravel Server Push Middleware


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
