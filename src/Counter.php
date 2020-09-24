<?php

namespace PHProm;

use Exception;

/**
 * counter client
 *
 * @package PHProm
 */
class Counter extends Metric
{
    /**
     * registers the metrics ONLY if it is not already registered
     *
     * @return bool
     * @throws Exception
     */
    protected function _register(): bool
    {
        return $this->getPHProm()->registerCounter(
            $this->getNamespace(),
            $this->getName(),
            $this->getDescription(),
            $this->getLabels()
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
        $this->getPHProm()->recordCounter(
            $this->getNamespace(),
            $this->getName(),
            $value,
            $labels
        );
    }
}
