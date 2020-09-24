<?php

namespace PHProm;

use Exception;

/**
 * summary client
 *
 * @package PHProm
 */
class Summary extends Metric
{
    /**
     * registers the metrics ONLY if it is not already registered
     *
     * @return bool
     * @throws Exception
     */
    protected function _register(): bool
    {
        return $this->getPHProm()->registerSummary(
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
        $this->getPHProm()->recordSummary(
            $this->getNamespace(),
            $this->getName(),
            $value,
            $labels
        );
    }
}
