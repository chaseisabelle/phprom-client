# phprom client
a client lib for [phprom](https://github.com/chaseisabelle/phprom), a prometheus metric datastore for php apps

---
### prerequisites

- install the [server](https://github.com/chaseisabelle/phprom)
- install the [client](https://packagist.org/packages/chaseisabelle/phprom-client)
    - `composer require chaseisabelle/phprom-client`
- install [grpc extension](https://grpc.io/docs/languages/php/quickstart/)
    - `pecl install grpc`
    - or use the [docker image](https://hub.docker.com/r/grpc/php)

---
### usage
- [instantiate a client](#instantiate-a-client)
- [register and record metrics manually](#register-and-record-metrics-manually)
- [register and record metrics automagically](#register-and-record-metrics-automagically)
- [create a timer to time and record latencies](#create-a-timer-to-time-and-record-latencies)
- [get the metrics for the scraper](#and-get-the-metrics-for-the-scraper)

#### instantiate a client
```php
// connect to the server
$phprom = new PHProm('127.0.0.1:3333');
```

#### register and record metrics manually

##### counter
```php
$phprom->registerCounter(
    'namespace',
    'name',
    'description',
    ['label1', 'label2'] //<< optional
);

$phprom->recordCounter(
    'namespace',
    'name',
    1.2345,
    ['label1' => 'foo', 'label2' => 'bar']
);
```

##### histogram
```php
$phprom->registerHistogram(
    'namespace',
    'name',
    'description',
    ['label1', 'label2'], //<< optional
    [0.1, 0.5, 1, 2, 5] //<< custom buckets, optional
);

$phprom->recordHistogram(
    'namespace',
    'name',
    1.2345,
    ['label1' => 'foo', 'label2' => 'bar']
);
```

##### summary
```php
$phprom->registerSummary(
    'namespace',
    'name',
    'description',
    ['label1', 'label2'] //<< optional
);

$phprom->recordSummary(
    'namespace',
    'name',
    1.2345,
    ['label1' => 'foo', 'label2' => 'bar']
);
```

##### gauge
```php
$phprom->registerGauge(
    'namespace',
    'name',
    'description',
    ['label1', 'label2'] //<< optional
);

$phprom->recordGauge(
    'namespace',
    'name',
    1.2345,
    ['label1' => 'foo', 'label2' => 'bar']
);
```

#### register and record metrics automagically

##### counter
```php
$counter = (new Counter($phprom))
    ->setNamespace('namespace')
    ->setName('name')
    ->setDescription('description')
    ->setLabels(['label1', 'label2']); //<< optional

$counter->record(
    1.2345, 
    ['label1' => 'foo', 'label2' => 'bar']
);
```

##### histogram
```php
$histogram = (new Histogram($phprom))
    ->setNamespace('namespace')
    ->setName('name')
    ->setDescription('description')
    ->setLabels(['label1', 'label2']) //<< optional
    ->setBuckets([0.1, 0.5, 1, 2, 5]); //<< optional

$histogram->record(
    1.2345, 
    ['label1' => 'foo', 'label2' => 'bar']
);
```

##### summary
```php
$summary = (new Summary($phprom))
    ->setNamespace('namespace')
    ->setName('name')
    ->setDescription('description')
    ->setLabels(['label1', 'label2']); //<< optional

$summary->record(
    1.2345, 
    ['label1' => 'foo', 'label2' => 'bar']
);
```

##### gauge
```php
$gauge = (new Gauge($phprom))
    ->setNamespace('namespace')
    ->setName('name')
    ->setDescription('description')
    ->setLabels(['label1', 'label2']); //<< optional

$gauge->record(
    1.2345, 
    ['label1' => 'foo', 'label2' => 'bar']
);
```

#### create a timer to time and record latencies
```php
$histogram = (new Histogram($phprom))
    ->setNamespace('namespace')
    ->setName('name')
    ->setDescription('description')
    ->setLabels(['label1', 'label2'])
    ->setBuckets(range(1, 10));

$timer = new Timer($histogram);

$timer->start();

sleep(rand(1, 10));

$timer->stop()
    ->record(['label1' => 'foo', 'label2' => 'bar'])
    ->reset();
```

#### and get the metrics for the scraper
```php
print($phprom->get());
```
