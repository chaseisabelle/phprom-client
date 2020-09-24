<?php

namespace PHProm\Test;

use PHPUnit\Framework\TestCase;
use ReflectionClass;

abstract class PHPromTestCase extends TestCase
{
    protected function setProtectedProperty(&$object, string $name, $value)
    {
        $reflection_property = (new ReflectionClass($object))->getProperty($name);

        $reflection_property->setAccessible(true);
        $reflection_property->setValue($object, $value);
    }

    protected function randBool(): bool
    {
        return boolval(rand(1, 0));
    }

    protected function randValue(): float
    {
        return floatval(rand(0, 10) . '.' . rand(0, 9999));
    }
}
