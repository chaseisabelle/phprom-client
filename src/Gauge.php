<?php

namespace ChaseIsabelle\PHProm;

class Gauge extends Metric
{
    protected function _register(): bool
    {
        return $this->getPHProm()->registerGauge(
            $this->getNamespace(),
            $this->getName(),
            $this->getDescription(),
            $this->getLabels()
        );
    }

    protected function _record(float $value, array $labels): void
    {
        $this->getPHProm()->recordGauge(
            $this->getNamespace(),
            $this->getName(),
            $value,
            $labels
        );
    }
}
