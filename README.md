# Как развернуть проект для тестирования

Установите [composer](https://getcomposer.org/download/)

Установите php версии не ниже 7.1

Клонируйте репозиторий

```bash
git clone git@git.epam.com:reni-ddc/RENI-DDC.git
```

Выполните в папке проекта:
```bash
composer install
```

Выполняйте команды "phpunit". Подробнее в разделе "Тестирование".

# Клиент

Может быть создан через фабрику ReninsApi\Factory или напрямую как экземпляр класса ReninsApi\Client\ApiVersion2.
Клиент может работать в режиме теста. В этом случае будут использован тестовый стенд сервиса.

Клиент использует REST-запросы и SOAP-запросы. REST для получения некоторых справочных данных.
Например: список марок и моделей авто. SOAP используется для расчетов по продуктам и для получения печатных форм.

# Контейнеры

Основная логика приложения построена на контейнерах - классах унаследованных от ReninsApi\Request\Container.
Контейнер может иметь свойства любого типа, в т.ч. другие контейнеры и коллекцию контейнеров.

* Контейнер моежт проверить сам себя на валидность. Для этого он содержит защищенное свойство $rules.
Валидация запускается явным образом (методы validate() или validateThrow()). Валидация проводится рекурсивно по
всем вложенным контейнерам и коллекциям
* После проверки можно запросить массив ошибок (метод getErrors()) 
* Контейнер может быть сериализован в XML или массив (методы toXml() и toArray()). Это также производится рекурсивно.
* Контейнер может быть создан из XML (метод fromXml() или стат. метод createFromXml()). Конечно контейнер создаст все
вложенные контейнеры и коллекции 

##Правила валидации

$rules представляет собой массив, ассоц. по именам свойств. Каждое свойство обязательно должно быть указано в $rules. Можно указать
пустой массив правил. Правила для каждого свойства можно указаывать в виде строки через запятую или в виде массива. 

Пример правил:

```php
    protected $rules = [
        'IsIP' => ['toLogical'],
        'FirstName' => ['toString', 'notEmpty'],
        'MiddleName' => ['toString', 'notEmpty'],
        'LastName' => ['toString', 'notEmpty'],
        'BirthDate' => ['toDate'],
        'Gender' => ['toString', 'in:M|F'],
        'MaritalStatus' => ['toInteger', 'between:1|4'],
        'HasChildren' => ['toLogical'],
        'DriveExperience' => ['toDate'],
        'Documents' => ['containerCollection:' . Document::class],
    ];
```
 
Правила начинающиеся на 'to' являются фильтрами. Они служат только для конвертации значения при записи в свойство.
Остальные правила являются валидаторами и проверяют значение свойства. Правила фильтрации обрабатываются ReninsApi\Request\Filter, правила валидации -
Правила фильтрации обрабатываются ReninsApi\Request\Validator.

Некоторые правила должны(могут) иметь параметры, указанные после двоеточия. Подробнее о правилах см. в ReninsApi\Request\Validator 

## Процесс валидации 

После вызова validate() можно запросить список ошибок методом getErrors(). Метод вернет плоский массив ошибок, ассоц. по названию свойств.

```php
[
    'Policy.Vehicle.Manufacturer' => '... текст ошибки ...'
    'Policy.Vehicle.Model' => '... текст ошибки ...'
    'Policy.Covers.0.code' => '... текст ошибки ...'
    'Policy.Covers.1.code' => '... текст ошибки ...'
]
```

Как видно из примера коллекции (Policy.Covers) также проверяются и результаты их проверок также собираются в список ошибок.

Метод validateThrow() создаст исключение ReninsApi\Request\ValidatorMultiException. Это исключение также имеет метод getErrors(),
которое вернет тот самый массив ошибок.

Метод validateThrow() вызывается перед каждым SOAP или REST запросом, а также после получения ответа и конвертации его в соотв. контейнер.

# Тестирование

Тесты разделены на 3 части (--testsuite): client, request, response.
Каждая часть имеет группы (--group): soap, client.
Если мы хотим запустить тест только для client и толко для soap, то

```bash
phpunit --testsuite client --group soap --debug
```

В папке tests/logs будут созданы логи тестирования в формате Html (Monolog).


