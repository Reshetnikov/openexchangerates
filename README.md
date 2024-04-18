


Задача
------------

Нужно зарегистрироваться тут https://openexchangerates.org/signup/free, получить ключ

И сделать компонент который по этому эндпойнту получает данные

https://oxr.readme.io/docs/latest-json, результат нужен в виде DTO.

К компоненту потенциально должно быть возможно прикрутить получеение данных и из других эндпойнтов этого апи.

### Решение находится в файлах:

- `components/ExchangeRateComponent.php`
- `dto/LatestDTO.php`
- `dto/LatestRateDTO.php`
- `commands/LatestController.php`
- `tests/unit/components/ExchangeRateComponentTest.php`
