# monolog-datadog-handler
Monolog Handler that uses [Datadog](https://www.datadoghq.com/)

Implemented for TCP Submission to Datadog API using [datadog/php-datadogstatsd](https://github.com/DataDog/php-datadogstatsd)


## Installation
```
composer require nohponex/monolog-datadog-handler
```

## Basic Usage
```php
<?php

use Nohponex\MonologDataDogHandler\DataDogHandler;

$tag = [
    sprintf(
        'environment:%s',
        'ci'
    ),
    sprintf(
        'instance:%s',
        'api'
    )
];

$log = new Logger('name');
$log->pushHandler(
    new DataDogHandler(
        'xxxx',
        'yyy',
        $tag,
        \Monolog\Logger::INFO
    )
);

```

## License

nohponex/monolog-datadog-handler is licensed under the MIT License - see the LICENSE file for details