<?php

namespace PHProm;

use Exception;
use Grpc\ChannelCredentials;
use Grpc\UnaryCall;
use PHProm\V1\GetRequest;
use PHProm\V1\GetResponse;
use PHProm\V1\RecordCounterRequest;
use PHProm\V1\RecordGaugeRequest;
use PHProm\V1\RecordHistogramRequest;
use PHProm\V1\RecordResponse;
use PHProm\V1\RecordSummaryRequest;
use PHProm\V1\RegisterCounterRequest;
use PHProm\V1\RegisterGaugeRequest;
use PHProm\V1\RegisterHistogramRequest;
use PHProm\V1\RegisterResponse;
use PHProm\V1\RegisterSummaryRequest;
use PHProm\V1\ServiceClient;

/**
 * the basic client
 *
 * @package PHProm
 */
class GRPCClient implements Client
{
    /**
     * @var ServiceClient
     */
    protected $client;

    /**
     * @param string $address
     * @throws Exception
     */
    public function __construct(string $address = '127.0.0.1:3333')
    {
        $this->client = new ServiceClient($address, [
            'credentials' => ChannelCredentials::createInsecure()
        ]);
    }

    /**
     * @inheritDoc
     */
    public function get(GetRequest $request): GetResponse
    {
        return $this->wait($this->client->Get($request));
    }

    /**
     * @inheritDoc
     */
    public function registerCounter(RegisterCounterRequest $request): RegisterResponse
    {
        return $this->wait($this->client->RegisterCounter($request));
    }

    /**
     * @inheritDoc
     */
    public function registerHistogram(RegisterHistogramRequest $request): RegisterResponse
    {
        return $this->wait($this->client->RegisterHistogram($request));
    }

    /**
     * @inheritDoc
     */
    public function registerSummary(RegisterSummaryRequest $request): RegisterResponse
    {
        return $this->wait($this->client->RegisterSummary($request));
    }

    /**
     * @inheritDoc
     */
    public function registerGauge(RegisterGaugeRequest $request): RegisterResponse
    {
        return $this->wait($this->client->RegisterGauge($request));
    }

    /**
     * @inheritDoc
     */
    public function recordCounter(RecordCounterRequest $request): RecordResponse
    {
        return $this->wait($this->client->RecordCounter($request));
    }

    /**
     * @inheritDoc
     */
    public function recordHistogram(RecordHistogramRequest $request): RecordResponse
    {
        return $this->wait($this->client->RecordHistogram($request));
    }

    /**
     * @inheritDoc
     */
    public function recordSummary(RecordSummaryRequest $request): RecordResponse
    {
        return $this->wait($this->client->RecordSummary($request));
    }

    /**
     * @inheritDoc
     */
    public function recordGauge(RecordGaugeRequest $request): RecordResponse
    {
        return $this->wait($this->client->RecordGauge($request));
    }

    /**
     * generic wrapper for the wait method and error handling
     *
     * @param UnaryCall $call
     * @return mixed
     * @throws Exception
     */
    protected function wait(UnaryCall $call)
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
