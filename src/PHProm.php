<?php

namespace PHProm;

use Exception;
use PHProm\V1\GetRequest;
use PHProm\V1\RecordCounterRequest;
use PHProm\V1\RecordGaugeRequest;
use PHProm\V1\RecordHistogramRequest;
use PHProm\V1\RecordSummaryRequest;
use PHProm\V1\RegisterCounterRequest;
use PHProm\V1\RegisterGaugeRequest;
use PHProm\V1\RegisterHistogramRequest;
use PHProm\V1\RegisterSummaryRequest;

/**
 * the basic client
 *
 * @package PHProm
 */
class PHProm
{
    /**
     * which interface to use
     */
    public const GRPC_API = 'grpc';
    public const REST_API = 'rest';

    /**
     * @var Client
     */
    protected $client;

    /**
     * @param string $address
     * @param string $api
     * @throws Exception
     */
    public function __construct(string $address = '127.0.0.1:3333', string $api = self::GRPC_API)
    {
        switch ($api) {
            case self::GRPC_API:
                $this->client = new GRPCClient($address);

                break;
            case self::REST_API:
                $this->client = new RESTClient($address);

                break;
            default:
                throw new Exception("unsupported api: $api");
        }
    }

    /**
     * gets the client
     *
     * @return Client
     */
    public function client(): Client
    {
        return $this->client;
    }

    /**
     * fetches the metrics as a string
     *
     * @return string
     * @throws Exception
     */
    public function get(): string
    {
        return $this->client()->get(new GetRequest())->getMetrics();
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
        $request = (new RegisterCounterRequest())
            ->setNamespace($namespace)
            ->setName($name)
            ->setDescription($description)
            ->setLabels($labels);

        return $this->client()
            ->registerCounter($request)
            ->getRegistered();
    }

    /**
     * @param string $namespace
     * @param string $name
     * @param string $description
     * @param array  $labels  the labels names without values
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
        $request = (new RegisterHistogramRequest())
            ->setNamespace($namespace)
            ->setName($name)
            ->setDescription($description)
            ->setLabels($labels)
            ->setBuckets($buckets);

        return $this->client()
            ->registerHistogram($request)
            ->getRegistered();
    }

    /**
     * @param string $namespace
     * @param string $name
     * @param string $description
     * @param array  $labels the labels names without values
     * @param array  $objectives
     * @param int    $maxAge
     * @param int    $ageBuckets
     * @param int    $bufCap
     * @return bool true if the metric was registered, false if it is already registered
     * @throws Exception
     */
    public function registerSummary(
        string $namespace,
        string $name,
        string $description,
        array $labels = [],
        array $objectives = [],
        int $maxAge = 0,
        int $ageBuckets = 0,
        int $bufCap = 0
    ): bool
    {
        $request = (new RegisterSummaryRequest())
            ->setNamespace($namespace)
            ->setName($name)
            ->setDescription($description)
            ->setLabels($labels)
            ->setObjectives($objectives)
            ->setAgeBuckets($ageBuckets)
            ->setMaxAge($maxAge)
            ->setBufCap($bufCap);

        return $this->client()
            ->registerSummary($request)
            ->getRegistered();
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
        $request = (new RegisterGaugeRequest())
            ->setNamespace($namespace)
            ->setName($name)
            ->setDescription($description)
            ->setLabels($labels);

        return $this->client()
            ->registerGauge($request)
            ->getRegistered();
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
        $request = (new RecordCounterRequest())
            ->setNamespace($namespace)
            ->setName($name)
            ->setValue($value)
            ->setLabels($labels);

        $this->client()
            ->recordCounter($request);
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
        $request = (new RecordHistogramRequest())
            ->setNamespace($namespace)
            ->setName($name)
            ->setValue($value)
            ->setLabels($labels);

        $this->client()
            ->recordHistogram($request);
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
        $request = (new RecordSummaryRequest())
            ->setNamespace($namespace)
            ->setName($name)
            ->setValue($value)
            ->setLabels($labels);

        $this->client()
            ->recordSummary($request);
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
        $request = (new RecordGaugeRequest())
            ->setNamespace($namespace)
            ->setName($name)
            ->setValue($value)
            ->setLabels($labels);

        $this->client()
            ->recordGauge($request);
    }


}
