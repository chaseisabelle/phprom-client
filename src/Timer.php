<?php

namespace PHProm;

use Exception;

/**
 * timer for latency histograms
 *
 * @package PHProm
 */
class Timer
{
    /**
     * @var Histogram
     */
    private $histogram = null;
    private $start     = null;
    private $stop      = null;

    /**
     * @param Histogram $histogram use `$histogram = new Histogram()`
     */
    public function __construct(Histogram $histogram)
    {
        $this->histogram = $histogram;
    }

    /**
     * @return Histogram
     */
    public function getHistogram(): Histogram
    {
        return $this->histogram;
    }

    /**
     * resets the timer
     *
     * @return $this
     */
    public function reset(): self
    {
        $this->start = null;
        $this->stop  = null;

        return $this;
    }

    /**
     * starts the timer
     *
     * @return $this
     */
    public function start(): self
    {
        $this->reset();

        $this->start = self::microtime();

        return $this;
    }

    /**
     * stop the timer
     * @return $this
     * @throws Exception if timer not started
     */
    public function stop(): self
    {
        if (!$this->start) {
            throw new Exception('timer not started');
        }

        $this->stop = self::microtime();

        return $this;
    }

    /**
     * get the latency interval
     *
     * @return float interval in seconds
     * @throws Exception if timer not stopped
     */
    public function interval(): float
    {
        if (!$this->stop) {
            throw new Exception('timer not stopped');
        }

        return $this->stop - $this->start;
    }

    /**
     * record latency with histogram
     *
     * @param array<string> $labels histogram labels with values
     * @return $this
     * @throws Exception if timer not stopped
     */
    public function record(array $labels = []): self
    {
        $this->getHistogram()->record($this->interval(), $labels);

        return $this;
    }

    /**
     * @return float current time in seconds
     */
    private static function microtime(): float
    {
        return microtime(true);
    }
}
