<?php

namespace PHProm\Test;

use PHProm\Counter;
use PHProm\Gauge;
use PHProm\PHProm;

class GaugeTest extends PHPromTestCase
{
    public function testRegister_success()
    {
        $namespace   = 'test';
        $name        = 'gauge';
        $description = 'hotdogs';
        $labels      = ['foo'];

        $phprom = $this->getMockBuilder(PHProm::class)
            ->disableOriginalConstructor()
            ->getMock();

        $phprom->expects($this->once())
            ->method('registerGauge')
            ->with(
                $namespace,
                $name,
                $description,
                $labels
            )
            ->willReturn($this->randBool());

        $gauge = (new Gauge($phprom))
            ->setNamespace($namespace)
            ->setName($name)
            ->setDescription($description)
            ->setLabels($labels);

        $gauge->register();

        $this->assertTrue($gauge->registered());

        $gauge->register();
    }

    public function testRecord_success()
    {
        $namespace   = 'test';
        $name        = 'gauge';
        $description = 'hotdogs';
        $labels      = ['foo' => 'bar'];
        $value       = $this->randValue();

        $phprom = $this->getMockBuilder(PHProm::class)
            ->disableOriginalConstructor()
            ->getMock();

        $phprom->expects($this->once())
            ->method('registerGauge')
            ->with(
                $namespace,
                $name,
                $description,
                array_keys($labels)
            )
            ->willReturn($this->randBool());

        $phprom->expects($this->once())
            ->method('recordGauge')
            ->with(
                $namespace,
                $name,
                $value,
                $labels
            )
            ->willReturn($this->randBool());

        $gauge = (new Gauge($phprom))
            ->setNamespace($namespace)
            ->setName($name)
            ->setDescription($description)
            ->setLabels(array_keys($labels));

        $gauge->record($value, $labels);

        $this->assertTrue($gauge->registered());
    }
}
