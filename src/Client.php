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
 * client interface
 *
 * @package PHProm
 */
interface Client
{
    /**
     * gets the metrics for the prometheus scraper
     *
     * @param GetRequest $request
     * @return GetResponse
     * @throws Exception on error
     */
    public function get(GetRequest $request): GetResponse;

    /**
     * register a counter metric
     *
     * @param RegisterCounterRequest $request
     * @return RegisterResponse
     * @throws Exception on error
     */
    public function registerCounter(RegisterCounterRequest $request): RegisterResponse;

    /**
     * register a histogram metric
     *
     * @param RegisterHistogramRequest $request
     * @return RegisterResponse
     * @throws Exception on error
     */
    public function registerHistogram(RegisterHistogramRequest $request): RegisterResponse;

    /**
     * register a summary metric
     *
     * @param RegisterSummaryRequest $request
     * @return RegisterResponse
     * @throws Exception on error
     */
    public function registerSummary(RegisterSummaryRequest $request): RegisterResponse;

    /**
     * register a gauge metric
     *
     * @param RegisterGaugeRequest $request
     * @return RegisterResponse
     * @throws Exception on error
     */
    public function registerGauge(RegisterGaugeRequest $request): RegisterResponse;

    /**
     * records the given metric - must be registered first!
     *
     * @param RecordCounterRequest $request
     * @return RecordResponse
     * @throws Exception on error
     */
    public function recordCounter(RecordCounterRequest $request): RecordResponse;

    /**
     * records the given metric - must be registered first!
     *
     * @param RecordHistogramRequest $request
     * @return RecordResponse
     * @throws Exception on error
     */
    public function recordHistogram(RecordHistogramRequest $request): RecordResponse;

    /**
     * records the given metric - must be registered first!
     *
     * @param RecordSummaryRequest $request
     * @return RecordResponse
     * @throws Exception on error
     */
    public function recordSummary(RecordSummaryRequest $request): RecordResponse;

    /**
     * records the given metric - must be registered first!
     *
     * @param RecordGaugeRequest $request
     * @return RecordResponse
     * @throws Exception on error
     */
    public function recordGauge(RecordGaugeRequest $request): RecordResponse;
}
