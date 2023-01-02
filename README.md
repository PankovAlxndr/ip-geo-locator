# Учебный проект для понимания паттернов Декоратор\Заместитель

В данном случае написана библиотека, которая определяем местоположение по ip-адресу.
Добавлена возможность писать свои ["локаторы"](example%2FDaDataLocator.php) и использовать их совместно с поставляемыми, чтобы, например, кешировать результат или логировать ошибки.

### Было реализовано ###
- Покрытие тестами - 100%
- [Декоратор](src%2FChainLocator.php), позволяющий указать несколько разных реализаций локатора (метода определения местоположения по ip) .
- [Заместитель](src%2FCacheLocator.php), позволяющий кешировать результаты и, в некоторых случаях, вовсе не вызывать метод служебного класса.
- [Декоратор](src%2FMuteLocator.php), позволяющий записать ошибку в лог и продолжить работу.

Цепочку декораторов конфигурирует клиент (вызывающий код) в любом порядке, тем самым динамически добавляя новую функциональность.

### Примеры использования [example.php](example%2Fexample.php)
```php
<?php

declare(strict_types=1);

use IpGeoLocator\CacheLocator;
use IpGeoLocator\ChainLocator;
use IpGeoLocator\Ip;
use IpGeoLocator\MuteLocator;
use IpGeoLocator\PsrLogErrorHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\Cache\Psr16Cache;

require_once '../vendor/autoload.php';
require_once 'DaDataLocator.php';

/*
 * Клиент реализует свой локатор, в данном случае через сервис dadata.ru.
 * Причем у данного "локатора" могут быть свои какие угодно зависимости, главное реализовать интерфейс (контракт)
 *
 * В данном случае мы декорируем вызов "нашего" DaDataLocator'а несколькими другими "локаторами"
 * ChainLocator -> CacheLocator -> MuteLocator -> DaDataLocator
 *
 * Chain локатор в данном примере избыточен, тк уу нас всего один DaDataLocator,
 * такая конфигурация в качестве примера.
 *
 * */

$locator = new ChainLocator(
    new CacheLocator(
        new MuteLocator(
            new DaDataLocator('...'),
            new PsrLogErrorHandler(new Logger('basic', [new StreamHandler('var/ip-geo-locator.log')]))
        ),
        new Psr16Cache(new FilesystemAdapter('cache-locator', 3600, 'var'))
    )
);
$location = $locator->locate(new Ip('46.229.184.75'));
var_dump($location);

/*
 * Ответ
 *
 * class IpGeoLocator\Location#30 (3) {
 *   private string $country =>
 *   string(12) "Россия"
 *   private ?string $region =>
 *   string(22) "Ярославская"
 *   private ?string $city =>
 *   string(18) "Ярославль"
 * }
*/
```

### Тесты

Запуск тестов:

``` bash
composer test
```