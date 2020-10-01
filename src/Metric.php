<?php

namespace PHProm;

/**
 * abstract metric client
 *
 * @package PHProm
 */
abstract class Metric
{
    /**
     * @var PHProm
     */
    private $phprom = null;

    /**
     * @var bool
     */
    private $registered = false;

    /**
     * @var string
     */
    private $namespace = null;

    /**
     * @var string
     */
    private $name = null;

    /**
     * @var string
     */
    private $description = null;

    /**
     * @var array
     */
    private $labels = [];

    /**
     * @param PHProm $phprom use `$phprom = new PHProm('127.0.0.1:3333')` to instantiate
     */
    public function __construct(PHProm $phprom)
    {
        $this->phprom = $phprom;
    }

    /**
     * @return PHProm
     */
    public function getPHProm(): PHProm
    {
        return $this->phprom;
    }

    /**
     * @return string the prometheus namespace (global prefix for your app)
     */
    public function getNamespace(): string
    {
        return $this->namespace;
    }

    /**
     * @param string $namespace the prometheus namespace (global prefix for your app)
     * @return $this
     */
    public function setNamespace(string $namespace): self
    {
        $this->namespace = $namespace;

        return $this;
    }

    /**
     * @return string the metric-specific name
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name the metric-specific name
     * @return $this
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string description of metric
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description description of metric
     * @return $this
     */
    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return array<string> label names
     */
    public function getLabels(): array
    {
        return $this->labels;
    }

    /**
     * @param array<string> $labels label names
     * @return $this
     */
    public function setLabels(array $labels): self
    {
        $this->labels = $labels;

        return $this;
    }

    /**
     * @return bool true if the metric has been registered, false if not
     */
    public function registered(): bool
    {
        return $this->registered;
    }

    /**
     * register the metric IF NOT already registered
     */
    public function register(): void
    {
        if (!$this->registered()) {
            $this->_register();
        }

        $this->registered = true;
    }

    /**
     * register the metric IF NOT already registered AND record metrics
     *
     * @param float         $value
     * @param array<String> $labels with values
     */
    public function record(float $value, array $labels = []): void
    {
        $this->register();
        $this->_record($value, $labels);
    }

    /**
     * @return bool
     */
    abstract protected function _register(): bool;

    /**
     * @param float         $value
     * @param array<string> $labels
     */
    abstract protected function _record(float $value, array $labels): void;
}
