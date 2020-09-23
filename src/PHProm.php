<?php

namespace PHProm;

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

class PHProm
{
    /**
     * @var ServiceClient
     */
    protected $client;

    public function __construct(string $address = '127.0.0.1:3333')
    {
        $this->client = new ServiceClient($address, [
            'credentials' => \Grpc\ChannelCredentials::createInsecure()
        ]);
    }

    public function get(): string
    {
        return $this->_wait($this->client->Get(new GetRequest()))->getMetrics();
    }

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

    protected function _wait(UnaryCall $call)
    {
        list($response, $status) = $call->wait();

        $status  = $status ?? new \stdClass();
        $code    = $status->code ?? null;
        $details = $status->details ?? null;

        if ($code || $details) {
            throw new \Exception($details ?? 'unkown grpc error', $code ?? 0);
        }

        if (!$response) {
            throw new \Exception('empty response with no error');
        }

        return $response;
    }
}
