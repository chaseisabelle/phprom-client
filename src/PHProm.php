<?php

namespace ChaseIsabelle\PHProm;

use PHProm\V1\GetRequest;
use PHProm\V1\RecordGaugeRequest;
use PHProm\V1\RecordHistogramRequest;
use PHProm\V1\RecordSummaryRequest;
use PHProm\V1\RegisterCounterRequest;
use PHProm\V1\RegisterHistogramRequest;
use PHProm\V1\RegisterSummaryRequest;
use PHProm\V1\ServiceClient;

class PHProm
{
    /**
     * @var ServiceClient
     */
    protected $client;

    public function __construct(string $address)
    {
        $this->client = new ServiceClient('host.docker.internal:3333', [
            'credentials' => Grpc\ChannelCredentials::createInsecure()
        ]);
    }

    public function get(): string
    {
        return $this->client->Get(new GetRequest())->getMetrics();
    }

    public function registerCounter(
        string $namespace,
        string $name,
        string $description,
        array $labels = []
    ): bool
    {
        return $this->client->RegisterCounter((new RegisterCounterRequest())
            ->setNamespace($namespace)
            ->setName($name)
            ->setDescription($description)
            ->setLabels($labels)
        )->getRegistered();
    }

    public function registerHistogram(
        string $namespace,
        string $name,
        string $description,
        array $labels = [],
        array $buckets = []
    ): bool
    {
        return $this->client->RegisterHistogram((new RegisterHistogramRequest())
            ->setNamespace($namespace)
            ->setName($name)
            ->setDescription($description)
            ->setLabels($labels)
            ->setBuckets($buckets)
        )->getRegistered();
    }

    public function registerSummary(
        string $namespace,
        string $name,
        string $description,
        array $labels = []
    ): bool
    {
        return $this->client->RegisterSummary((new RegisterSummaryRequest())
            ->setNamespace($namespace)
            ->setName($name)
            ->setDescription($description)
            ->setLabels($labels)
        )->getRegistered();
    }

    public function registerGauge(
        string $namespace,
        string $name,
        string $description,
        array $labels = []
    ): bool
    {
        return $this->client->RegisterSummary((new RegisterSummaryRequest())
            ->setNamespace($namespace)
            ->setName($name)
            ->setDescription($description)
            ->setLabels($labels)
        )->getRegistered();
    }

    public function recordCounter(
        string $namespace,
        string $name,
        float $value = 1,
        array $labels = []
    )
    {
        $this->client->RecordHistogram((new RecordCounterRequest())
            ->setNamespace($namespace)
            ->setName($name)
            ->setValue($value)
            ->setLabels($labels)
        );
    }

    public function recordHistogram(
        string $namespace,
        string $name,
        float $value = 1,
        array $labels = [],
        array $buckets = []
    )
    {
        $this->client->RecordHistogram((new RecordHistogramRequest())
            ->setNamespace($namespace)
            ->setName($name)
            ->setValue($value)
            ->setLabels($labels)
            ->setBuckets($buckets)
        );
    }

    public function recordSummary(
        string $namespace,
        string $name,
        float $value = 1,
        array $labels = []
    )
    {
        $this->client->RecordSummary((new RecordSummaryRequest())
            ->setNamespace($namespace)
            ->setName($name)
            ->setValue($value)
            ->setLabels($labels)
        );
    }

    public function recordGauge(
        string $namespace,
        string $name,
        float $value = 1,
        array $labels = []
    )
    {
        $this->client->RecordGauge((new RecordGaugeRequest())
            ->setNamespace($namespace)
            ->setName($name)
            ->setValue($value)
            ->setLabels($labels)
        );
    }
}
