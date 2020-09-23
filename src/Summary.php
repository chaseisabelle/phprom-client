<?php

namespace PHProm;

class Summary extends Metric
{
    protected function _register(): bool
    {
        return $this->getPHProm()->registerSummary(
            $this->getNamespace(),
            $this->getName(),
            $this->getDescription(),
            $this->getLabels()
        );
    }

    protected function _record(float $value, array $labels): void
    {
        $this->getPHProm()->recordSummary(
            $this->getNamespace(),
            $this->getName(),
            $value,
            $labels
        );
    }
}
