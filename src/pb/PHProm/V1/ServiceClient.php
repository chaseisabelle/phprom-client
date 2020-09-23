<?php
// GENERATED CODE -- DO NOT EDIT!

namespace PHProm\V1;

/**
 */
class ServiceClient extends \Grpc\BaseStub {

    /**
     * @param string $hostname hostname
     * @param array $opts channel options
     * @param \Grpc\Channel $channel (optional) re-use channel object
     */
    public function __construct($hostname, $opts, $channel = null) {
        parent::__construct($hostname, $opts, $channel);
    }

    /**
     * @param \PHProm\V1\GetRequest $argument input argument
     * @param array $metadata metadata
     * @param array $options call options
     * @return \Grpc\UnaryCall
     */
    public function Get(\PHProm\V1\GetRequest $argument,
      $metadata = [], $options = []) {
        return $this->_simpleRequest('/PHProm.v1.Service/Get',
        $argument,
        ['\PHProm\V1\GetResponse', 'decode'],
        $metadata, $options);
    }

    /**
     * @param \PHProm\V1\RegisterCounterRequest $argument input argument
     * @param array $metadata metadata
     * @param array $options call options
     * @return \Grpc\UnaryCall
     */
    public function RegisterCounter(\PHProm\V1\RegisterCounterRequest $argument,
      $metadata = [], $options = []) {
        return $this->_simpleRequest('/PHProm.v1.Service/RegisterCounter',
        $argument,
        ['\PHProm\V1\RegisterResponse', 'decode'],
        $metadata, $options);
    }

    /**
     * @param \PHProm\V1\RegisterHistogramRequest $argument input argument
     * @param array $metadata metadata
     * @param array $options call options
     * @return \Grpc\UnaryCall
     */
    public function RegisterHistogram(\PHProm\V1\RegisterHistogramRequest $argument,
      $metadata = [], $options = []) {
        return $this->_simpleRequest('/PHProm.v1.Service/RegisterHistogram',
        $argument,
        ['\PHProm\V1\RegisterResponse', 'decode'],
        $metadata, $options);
    }

    /**
     * @param \PHProm\V1\RegisterSummaryRequest $argument input argument
     * @param array $metadata metadata
     * @param array $options call options
     * @return \Grpc\UnaryCall
     */
    public function RegisterSummary(\PHProm\V1\RegisterSummaryRequest $argument,
      $metadata = [], $options = []) {
        return $this->_simpleRequest('/PHProm.v1.Service/RegisterSummary',
        $argument,
        ['\PHProm\V1\RegisterResponse', 'decode'],
        $metadata, $options);
    }

    /**
     * @param \PHProm\V1\RegisterGaugeRequest $argument input argument
     * @param array $metadata metadata
     * @param array $options call options
     * @return \Grpc\UnaryCall
     */
    public function RegisterGauge(\PHProm\V1\RegisterGaugeRequest $argument,
      $metadata = [], $options = []) {
        return $this->_simpleRequest('/PHProm.v1.Service/RegisterGauge',
        $argument,
        ['\PHProm\V1\RegisterResponse', 'decode'],
        $metadata, $options);
    }

    /**
     * @param \PHProm\V1\RecordCounterRequest $argument input argument
     * @param array $metadata metadata
     * @param array $options call options
     * @return \Grpc\UnaryCall
     */
    public function RecordCounter(\PHProm\V1\RecordCounterRequest $argument,
      $metadata = [], $options = []) {
        return $this->_simpleRequest('/PHProm.v1.Service/RecordCounter',
        $argument,
        ['\PHProm\V1\RecordResponse', 'decode'],
        $metadata, $options);
    }

    /**
     * @param \PHProm\V1\RecordHistogramRequest $argument input argument
     * @param array $metadata metadata
     * @param array $options call options
     * @return \Grpc\UnaryCall
     */
    public function RecordHistogram(\PHProm\V1\RecordHistogramRequest $argument,
      $metadata = [], $options = []) {
        return $this->_simpleRequest('/PHProm.v1.Service/RecordHistogram',
        $argument,
        ['\PHProm\V1\RecordResponse', 'decode'],
        $metadata, $options);
    }

    /**
     * @param \PHProm\V1\RecordSummaryRequest $argument input argument
     * @param array $metadata metadata
     * @param array $options call options
     * @return \Grpc\UnaryCall
     */
    public function RecordSummary(\PHProm\V1\RecordSummaryRequest $argument,
      $metadata = [], $options = []) {
        return $this->_simpleRequest('/PHProm.v1.Service/RecordSummary',
        $argument,
        ['\PHProm\V1\RecordResponse', 'decode'],
        $metadata, $options);
    }

    /**
     * @param \PHProm\V1\RecordGaugeRequest $argument input argument
     * @param array $metadata metadata
     * @param array $options call options
     * @return \Grpc\UnaryCall
     */
    public function RecordGauge(\PHProm\V1\RecordGaugeRequest $argument,
      $metadata = [], $options = []) {
        return $this->_simpleRequest('/PHProm.v1.Service/RecordGauge',
        $argument,
        ['\PHProm\V1\RecordResponse', 'decode'],
        $metadata, $options);
    }

}
