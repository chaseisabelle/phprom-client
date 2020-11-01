<?php

namespace PHProm;

use Exception;
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

/**
 * the basic client
 *
 * @package PHProm
 */
class RESTClient implements Client
{
    /**
     * @var string
     */
    protected $address;

    /**
     * @param string $address
     * @throws Exception
     */
    public function __construct(string $address = '127.0.0.1:3333')
    {
        if (empty(parse_url($address)['scheme'])) {
            $address = 'http://' . $address;
        }

        $this->address = $address;
    }

    /**
     * @return string
     */
    public function address(): string
    {
        return $this->address;
    }

    /**
     * @inheritDoc
     */
    public function get(GetRequest $request): GetResponse
    {
        $metrics = $this->curl($this->address() . '/metrics', 'GET', $request->serializeToJsonString(), false);

        return (new GetResponse())
            ->setMetrics($metrics);
    }

    /**
     * @inheritDoc
     */
    public function registerCounter(RegisterCounterRequest $request): RegisterResponse
    {
        return $this->register('counter', $request->serializeToJsonString());
    }

    /**
     * @inheritDoc
     */
    public function registerHistogram(RegisterHistogramRequest $request): RegisterResponse
    {
        return $this->register('histogram', $request->serializeToJsonString());
    }

    /**
     * @inheritDoc
     */
    public function registerSummary(RegisterSummaryRequest $request): RegisterResponse
    {
        return $this->register('summary', $request->serializeToJsonString());
    }

    /**
     * @inheritDoc
     */
    public function registerGauge(RegisterGaugeRequest $request): RegisterResponse
    {
        return $this->register('gauge', $request->serializeToJsonString());
    }

    /**
     * @inheritDoc
     */
    public function recordCounter(RecordCounterRequest $request): RecordResponse
    {
        return $this->record('counter', $request->serializeToJsonString());
    }

    /**
     * @inheritDoc
     */
    public function recordHistogram(RecordHistogramRequest $request): RecordResponse
    {
        return $this->record('histogram', $request->serializeToJsonString());
    }

    /**
     * @inheritDoc
     */
    public function recordSummary(RecordSummaryRequest $request): RecordResponse
    {
        return $this->record('summary', $request->serializeToJsonString());
    }

    /**
     * @inheritDoc
     */
    public function recordGauge(RecordGaugeRequest $request): RecordResponse
    {
        return $this->record('gauge', $request->serializeToJsonString());
    }

    /**
     * helper for register methods
     *
     * @param string $type
     * @param string $body
     * @return RegisterResponse
     * @throws Exception
     */
    protected function register(string $type, string $body): RegisterResponse
    {
        $reply      = $this->curl($this->address() . '/register/' . $type, 'POST', $body);
        $registered = boolval($reply['registered'] ?? null);

        return (new RegisterResponse())
            ->setRegistered($registered);
    }

    /**
     * helper for record methods
     *
     * @param string $type
     * @param string $body
     * @return RecordResponse
     * @throws Exception
     */
    protected function record(string $type, string $body): RecordResponse
    {
        $this->curl($this->address() . '/record/' . $type, 'POST', $body, false);

        return new RecordResponse();
    }

    /**
     * does the curl stuff
     *
     * @param string $url
     * @param string $method
     * @param string $body
     * @param bool   $decode
     * @return string|bool
     * @throws Exception
     */
    protected function curl(string $url, string $method, string $body, bool $decode = true)
    {
        $curl = curl_init($url);

        if (!$curl) {
            throw new Exception("failed to init $method $url");
        }

        try {
            if (!curl_setopt_array($curl, [
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_CUSTOMREQUEST  => $method,
                CURLOPT_POSTFIELDS     => $body
            ])) {
                throw new Exception(curl_error($curl));
            }

            $response = curl_exec($curl);

            if (!is_string($response)) {
                throw new Exception("failed to query $method $url");
            }

            $status = curl_getinfo($curl, CURLINFO_RESPONSE_CODE);

            if ($status !== 200) {
                throw new Exception("received $status for query $method $url");
            }

            if ($decode) {
                $response = json_decode($response, true);

                if (!is_array($response)) {
                    throw new Exception("failed to decode $method $url: " . json_last_error_msg());
                }
            }

            return $response;
        } catch (Exception $exception) {
            throw $exception;
        } finally {
            curl_close($curl);
        }
    }
}
