<?php

namespace PHProm\Test;

use PHProm\Counter;
use PHProm\PHProm;

class CounterTest extends PHPromTestCase
{
    public function testRegister_success()
    {
        $namespace   = 'test';
        $name        = 'counter';
        $description = 'hotdogs';
        $labels      = ['foo'];

        $phprom = $this->getMockBuilder(PHProm::class)
            ->disableOriginalConstructor()
            ->getMock();

        $phprom->expects($this->once())
            ->method('registerCounter')
            ->with(
                $namespace,
                $name,
                $description,
                $labels
            )
            ->willReturn($this->randBool());

        $counter = (new Counter($phprom))
            ->setNamespace($namespace)
            ->setName($name)
            ->setDescription($description)
            ->setLabels($labels);

        $counter->register();

        $this->assertTrue($counter->registered());

        $counter->register();
    }

    public function testRecord_success()
    {
        $namespace   = 'test';
        $name        = 'counter';
        $description = 'hotdogs';
        $labels      = ['foo' => 'bar'];
        $value       = $this->randValue();

        $phprom = $this->getMockBuilder(PHProm::class)
            ->disableOriginalConstructor()
            ->getMock();

        $phprom->expects($this->once())
            ->method('registerCounter')
            ->with(
                $namespace,
                $name,
                $description,
                array_keys($labels)
            )
            ->willReturn($this->randBool());

        $phprom->expects($this->once())
            ->method('recordCounter')
            ->with(
                $namespace,
                $name,
                $value,
                $labels
            )
            ->willReturn($this->randBool());

        $counter = (new Counter($phprom))
            ->setNamespace($namespace)
            ->setName($name)
            ->setDescription($description)
            ->setLabels(array_keys($labels));

        $counter->record($value, $labels);

        $this->assertTrue($counter->registered());
    }

    public function testRegRec_dynLabels()
    {
        $namespace   = 'test';
        $name        = 'counter';
        $description = 'hotdogs';
        $labels      = ['foo' => 'bar'];
        $value       = $this->randValue();

        $phprom = $this->getMockBuilder(PHProm::class)
            ->disableOriginalConstructor()
            ->getMock();

        $phprom->expects($this->once())
            ->method('registerCounter')
            ->with(
                $namespace,
                $name,
                $description,
                array_keys($labels)
            )
            ->willReturn($this->randBool());

        $phprom->expects($this->once())
            ->method('recordCounter')
            ->with(
                $namespace,
                $name,
                $value,
                $labels
            )
            ->willReturn($this->randBool());

        $counter = (new Counter($phprom))
            ->setNamespace($namespace)
            ->setName($name)
            ->setDescription($description);

        $counter->record($value, $labels);

        $this->assertTrue($counter->registered());
    }
}
