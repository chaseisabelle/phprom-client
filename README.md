# phprom client
a client lib for [phprom](https://github.com/chaseisabelle/phprom), a prometheus metric datastore for php apps

---

### prerequisites

- install the [server](https://github.com/chaseisabelle/phprom)
- install the [client](https://packagist.org/packages/chaseisabelle/phprom-client)
    - `composer require chaseisabelle/phprom-client`

### usage

```php
// connect to the server
$phprom = new PHProm('127.0.0.1:3333');

// register and record your metrics manually
$phprom->registerCounter(
    'namespace',
    'name',
    'description',
    ['label1', 'label2']
);

$phprom->recordCounter(
    'namespace',
    'name',
    1.2345,
    ['label1' => 'foo', 'label2' => 'bar']
);

// instantiate your metrics for auto-register and record
$counter = (new Counter($phprom))
    ->setNamespace('namespace')
    ->setName('name')
    ->setDescription('description')
    ->setLabels(['label1', 'label2']);

$counter->record(
    1.2345, 
    ['label1' => 'foo', 'label2' => 'bar']
);

// create a timer to time and record latencies
$histogram = (new Histogram($phprom))
    ->setNamespace('namespace')
    ->setName('name')
    ->setDescription('description')
    ->setLabels(['label1', 'label2']);

$timer = new Timer($histogram);

$timer->start();
sleep(rand(1, 10));
$timer->stop()
    ->record(['label1' => 'foo', 'label2' => 'bar'])
    ->reset();

// and get the metrics for the scraper
print($phprom->get());
```
