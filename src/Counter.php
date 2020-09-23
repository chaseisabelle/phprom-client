<?php

namespace ChaseIsabelle\PHProm;

class Counter extends Metric
{
    protected function _register(): bool
    {
        return $this->getPHProm()->registerCounter(
            $this->getNamespace(),
            $this->getName(),
            $this->getDescription(),
            $this->getLabels()
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
