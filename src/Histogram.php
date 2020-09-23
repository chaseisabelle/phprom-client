<?php

namespace ChaseIsabelle\PHProm;

class Histogram extends Metric
{
    private $buckets = [];

    public function getBuckets(): array
    {
        return $this->buckets;
    }

    public function setBuckets(array $buckets): self
    {
        $this->buckets = $buckets;

        return $this;
    }

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

    protected function _record(float $value, array $labels): void
    {
        $this->getPHProm()->recordCounter(
            $this->getNamespace(),
            $this->getName(),
            $value,
            $labels
        );
    }
}
