# phprom client
a client lib for [phprom](https://github.com/chaseisabelle/phprom), a prometheus metric datastore for php apps

---
### example
see a fully functional example [here](https://github.com/chaseisabelle/phprom-example)

---
### prerequisites

install and run the [server](https://github.com/chaseisabelle/phprom)

### install

- install the [client](https://packagist.org/packages/chaseisabelle/phprom-client) or [bundle](https://github.com/chaseisabelle/phprom-bundle)
    - `composer require chaseisabelle/phprom-client`
    - `composer require chaseisabelle/phprom-bundle`
    
#### install grpc requirements
- NOTE: this is only required if you are using the grpc api
- install grpc
    - `composer require 'grpc/grpc:1.30.0' 'google/protobuf:3.13.*'`
- install [grpc extension](https://grpc.io/docs/languages/php/quickstart/)
    - `pecl install grpc`
    - or use the [docker image](https://hub.docker.com/r/grpc/php)

---
### usage
- [instantiate a client](#instantiate-a-client)
- [get the metrics for the prometheus scraper](#get-the-metrics-for-the-prometheus-scraper)
- [register and record metrics automagically](#register-and-record-metrics-automagically)
- [create a timer to time and record latencies](#create-a-timer-to-time-and-record-latencies)
- [register and record metrics manually](#register-and-record-metrics-manually)

#### instantiate a client

##### grpc
```php
// connect to the server using grpc
$phprom = new PHProm('127.0.0.1:3333');
// or
$phprom = new PHProm('127.0.0.1:3333', PHProm::GRPC_API);
```

##### rest/http
```php
// or connect to the server using rest/http
$phprom = new PHProm('127.0.0.1:3333', PHProm::REST_API);
```

*!NOTE!* see the suggested packages list in `composer.json`:
```json
  "suggest": {
    "ext-grpc": "* required for grpc api",
    "ext-curl": "* required for rest/http api"
  }
```

##### grpc vs rest/http
both the grpc and rest/http api benchmark at about the same
when running on a local network:
- min: 2ms
- max: 12ms
- avg: ~6ms

#### get the metrics for the prometheus scraper
```php
print($phprom->get());
// or...
echo $phprom->get();
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
    ->setLabels(['label1', 'label2']) //<< optional
    ->setObjectives([0.1, 0.5, 1, 2, 5]) //<< optional
    ->setMaxAge(0) //<< optional
    ->setAgeBuckets(0) //<< optional
    ->setBufCap(0); //<< optional

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
    ['label1', 'label2'], //<< optional
    [0.1, 0.5, 1, 2, 5], //<< objectives, optional
    0, //<< max age, optional
    0, //<< age buckets, optional
    0 //<< buf cap, optional
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
