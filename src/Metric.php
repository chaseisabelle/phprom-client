<?php

namespace PHProm;

abstract class Metric
{
    /**
     * @var PHProm
     */
    private $phprom      = null;
    private $registered  = false;
    private $namespace   = null;
    private $name        = null;
    private $description = null;
    private $labels      = [];

    public function __construct(PHProm $phprom)
    {
        $this->phprom = $phprom;
    }

    public function getPHProm(): PHProm
    {
        return $this->phprom;
    }

    public function getNamespace(): string
    {
        return $this->namespace;
    }

    public function setNamespace(string $namespace): self
    {
        $this->namespace = $namespace;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getLabels(): array
    {
        return $this->labels;
    }

    public function setLabels(array $labels): self
    {
        $this->labels = $labels;

        return $this;
    }

    public function registered(): bool
    {
        return $this->registered;
    }

    public function register(): void
    {
        if (!$this->registered()) {
            $this->_register();
        }

        $this->registered = true;
    }

    public function record(float $value, array $labels = []): void {
        $this->register();
        $this->_record($value, $labels);
    }

    abstract protected function _register(): bool;

    abstract protected function _record(float $value, array $labels): void;
}
