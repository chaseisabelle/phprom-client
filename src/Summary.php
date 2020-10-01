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
    protected $objectives = [];
    protected $maxAge     = 0;
    protected $ageBuckets = 0;
    protected $bufCap     = 0;

    /**
     * @return array
     */
    public function getObjectives(): array
    {
        return $this->objectives;
    }

    /**
     * @param array $objectives
     * @return Summary
     */
    public function setObjectives(array $objectives): self
    {
        $this->objectives = $objectives;

        return $this;
    }

    /**
     * @return int
     */
    public function getMaxAge(): int
    {
        return $this->maxAge;
    }

    /**
     * @param int $maxAge
     * @return Summary
     */
    public function setMaxAge(int $maxAge): self
    {
        $this->maxAge = $maxAge;

        return $this;
    }

    /**
     * @return int
     */
    public function getAgeBuckets(): int
    {
        return $this->ageBuckets;
    }

    /**
     * @param int $ageBuckets
     * @return Summary
     */
    public function setAgeBuckets(int $ageBuckets): self
    {
        $this->ageBuckets = $ageBuckets;

        return $this;
    }

    /**
     * @return int
     */
    public function getBufCap(): int
    {
        return $this->bufCap;
    }

    /**
     * @param int $bufCap
     * @return Summary
     */
    public function setBufCap(int $bufCap): self
    {
        $this->bufCap = $bufCap;

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
