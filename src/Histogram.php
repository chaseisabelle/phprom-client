<?php

namespace PHProm;

use Exception;

/**
 * histogram client
 *
 * @package PHProm
 */
class Histogram extends Metric
{
    /**
     * @var array
     */
    private $buckets = [];

    /**
     * @return array the custom buckets OR empty array if using defaults
     */
    public function getBuckets(): array
    {
        return $this->buckets;
    }

    /**
     * @param array $buckets custom buckets
     * @return $this
     */
    public function setBuckets(array $buckets): self
    {
        $this->buckets = $buckets;

        return $this;
    }

    /**
     * registers the metrics ONLY if it is not already registered
     *
     * @return bool
     * @throws Exception
     */
    protected function _register(): bool
    {
        return $this->getPHProm()->registerHistogram(
            $this->getNamespace(),
            $this->getName(),
            $this->getDescription(),
            $this->getLabels(),
            $this->getBuckets()
        );
    }

    /**
     * registers the metrics ONLY if it is not already registered AND records the metric
     *
     * @param float $value
     * @param array $labels
     * @throws Exception
     */
    protected function _record(float $value, array $labels): void
    {
        $this->getPHProm()->recordHistogram(
            $this->getNamespace(),
            $this->getName(),
            $value,
            $labels
        );
    }
}
