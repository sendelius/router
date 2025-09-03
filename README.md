# Sendelius Router

<p>
<a href="https://packagist.org/packages/sendelius/router"><img src="https://img.shields.io/packagist/dt/sendelius/router" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/sendelius/router"><img src="https://img.shields.io/packagist/v/sendelius/router" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/sendelius/router"><img src="https://img.shields.io/packagist/l/sendelius/router" alt="License"></a>
</p>

Лёгкая и удобная библиотека для маршрутизации HTTP-запросов.

## Установка

```
composer require sendelius/router
```

## Пример использования
```php
require 'vendor/autoload.php';

use \Sendelius\Router\Router;

$router = new Router();
$router->rule('/api/v1/{controller:l}/{method:ls}');
$router->start();
```
