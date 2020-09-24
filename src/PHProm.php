<?php

namespace PHProm;

use Exception;
use Grpc\UnaryCall;
use PHProm\V1\GetRequest;
use PHProm\V1\RecordCounterRequest;
use PHProm\V1\RecordGaugeRequest;
use PHProm\V1\RecordHistogramRequest;
use PHProm\V1\RecordSummaryRequest;
use PHProm\V1\RegisterCounterRequest;
use PHProm\V1\RegisterGaugeRequest;
use PHProm\V1\RegisterHistogramRequest;
use PHProm\V1\RegisterSummaryRequest;
use PHProm\V1\ServiceClient;

/**
 * the basic client
 *
 * @package PHProm
 */
class PHProm
{
    /**
     * @var ServiceClient
     */
    protected $client;

    /**
     * @param string $address
     */
    public function __construct(string $address = '127.0.0.1:3333')
    {
        $this->client = new ServiceClient($address, [
            'credentials' => \Grpc\ChannelCredentials::createInsecure()
        ]);
    }

    /**
     * fetches the metrics as a string
     *
     * @return string
     * @throws Exception
     */
    public function get(): string
    {
        return $this->_wait($this->client->Get(new GetRequest()))->getMetrics();
    }

    /**
     * @param string $namespace
     * @param string $name
     * @param string $description
     * @param array  $labels the labels names without values
     * @return bool true if the metric was registered, false if it is already registered
     * @throws Exception
     */
    public function registerCounter(
        string $namespace,
        string $name,
        string $description,
        array $labels = []
    ): bool
    {
        return $this->_wait($this->client->RegisterCounter((new RegisterCounterRequest())
            ->setNamespace($namespace)
            ->setName($name)
            ->setDescription($description)
            ->setLabels($labels)
        ))->getRegistered();
    }

    /**
     * @param string $namespace
     * @param string $name
     * @param string $description
     * @param array  $labels the labels names without values
     * @param array  $buckets custom bucket values - use default if not sure
     * @return bool true if the metric was registered, false if it is already registered
     * @throws Exception
     */
    public function registerHistogram(
        string $namespace,
        string $name,
        string $description,
        array $labels = [],
        array $buckets = []
    ): bool
    {
        return $this->_wait($this->client->RegisterHistogram((new RegisterHistogramRequest())
            ->setNamespace($namespace)
            ->setName($name)
            ->setDescription($description)
            ->setLabels($labels)
            ->setBuckets($buckets)
        ))->getRegistered();
    }

    /**
     * @param string $namespace
     * @param string $name
     * @param string $description
     * @param array  $labels the labels names without values
     * @return bool true if the metric was registered, false if it is already registered
     * @throws Exception
     */
    public function registerSummary(
        string $namespace,
        string $name,
        string $description,
        array $labels = []
    ): bool
    {
        return $this->_wait($this->client->RegisterSummary((new RegisterSummaryRequest())
            ->setNamespace($namespace)
            ->setName($name)
            ->setDescription($description)
            ->setLabels($labels)
        ))->getRegistered();
    }

    /**
     * @param string $namespace
     * @param string $name
     * @param string $description
     * @param array  $labels the labels names without values
     * @return bool true if the metric was registered, false if it is already registered
     * @throws Exception
     */
    public function registerGauge(
        string $namespace,
        string $name,
        string $description,
        array $labels = []
    ): bool
    {
        return $this->_wait($this->client->RegisterGauge((new RegisterGaugeRequest())
            ->setNamespace($namespace)
            ->setName($name)
            ->setDescription($description)
            ->setLabels($labels)
        ))->getRegistered();
    }

    /**
     * records the given metric - must be registered first!
     *
     * @param string    $namespace
     * @param string    $name
     * @param float|int $value
     * @param array     $labels map with label name => label value (eg. ['foo' => 'bar'])
     * @throws Exception
     */
    public function recordCounter(
        string $namespace,
        string $name,
        float $value = 1,
        array $labels = []
    )
    {
        $this->_wait($this->client->RecordCounter((new RecordCounterRequest())
            ->setNamespace($namespace)
            ->setName($name)
            ->setValue($value)
            ->setLabels($labels)
        ));
    }

    /**
     * records the given metric - must be registered first!
     *
     * @param string    $namespace
     * @param string    $name
     * @param float|int $value
     * @param array     $labels map with label name => label value (eg. ['foo' => 'bar'])
     * @throws Exception
     */
    public function recordHistogram(
        string $namespace,
        string $name,
        float $value = 1,
        array $labels = []
    )
    {
        $this->_wait($this->client->RecordHistogram((new RecordHistogramRequest())
            ->setNamespace($namespace)
            ->setName($name)
            ->setValue($value)
            ->setLabels($labels)
        ));
    }

    /**
     * records the given metric - must be registered first!
     *
     * @param string    $namespace
     * @param string    $name
     * @param float|int $value
     * @param array     $labels map with label name => label value (eg. ['foo' => 'bar'])
     * @throws Exception
     */
    public function recordSummary(
        string $namespace,
        string $name,
        float $value = 1,
        array $labels = []
    )
    {
        $this->_wait($this->client->RecordSummary((new RecordSummaryRequest())
            ->setNamespace($namespace)
            ->setName($name)
            ->setValue($value)
            ->setLabels($labels)
        ));
    }

    /**
     * records the given metric - must be registered first!
     *
     * @param string    $namespace
     * @param string    $name
     * @param float|int $value
     * @param array     $labels map with label name => label value (eg. ['foo' => 'bar'])
     * @throws Exception
     */
    public function recordGauge(
        string $namespace,
        string $name,
        float $value = 1,
        array $labels = []
    )
    {
        $this->_wait($this->client->RecordGauge((new RecordGaugeRequest())
            ->setNamespace($namespace)
            ->setName($name)
            ->setValue($value)
            ->setLabels($labels)
        ));
    }

    /**
     * generic wrapper for the wait method and error handling
     *
     * @param UnaryCall $call
     * @return mixed
     * @throws Exception
     */
    protected function _wait(UnaryCall $call)
    {
        list($response, $status) = $call->wait();

        $status  = $status ?? new \stdClass();
        $code    = $status->code ?? null;
        $details = $status->details ?? null;

        if ($code || $details) {
            throw new Exception($details ?? 'unkown grpc error', $code ?? 0);
        }

        if (!$response) {
            throw new Exception('empty response with no error');
        }

        return $response;
    }
}
