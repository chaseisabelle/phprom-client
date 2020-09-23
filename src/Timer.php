<?php

namespace PHProm;

class Timer
{
    /**
     * @var Histogram
     */
    private $histogram = null;
    private $start     = null;
    private $stop      = null;

    public function __construct(Histogram $histogram)
    {
        $this->histogram = $histogram;
    }

    public function getHistogram(): Histogram
    {
        return $this->histogram;
    }

    public function reset(): self
    {
        $this->start = null;
        $this->stop  = null;

        return $this;
    }

    public function start(): self
    {
        $this->reset();

        $this->start = self::microtime();

        return $this;
    }

    public function stop(): self
    {
        if (!$this->start) {
            throw new \Exception('stopwatch not started');
        }

        $this->stop = self::microtime();

        return $this;
    }

    public function interval(): float
    {
        if (!$this->stop) {
            throw new \Exception('stopwatch not stopped');
        }

        return $this->stop - $this->start;
    }

    public function record(array $labels = []): self
    {
        $this->getHistogram()->record($this->interval(), $labels);

        return $this;
    }

    private static function microtime(): float
    {
        return microtime(true);
    }
}
