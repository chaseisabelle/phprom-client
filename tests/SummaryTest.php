<?php

namespace PHProm\Test;

use PHProm\Counter;
use PHProm\PHProm;
use PHProm\Summary;

class SummaryTest extends PHPromTestCase
{
    public function testRegister_success()
    {
        $namespace   = 'test';
        $name        = 'summary';
        $description = 'hotdogs';
        $labels      = ['foo'];

        $phprom = $this->getMockBuilder(PHProm::class)
            ->disableOriginalConstructor()
            ->getMock();

        $phprom->expects($this->once())
            ->method('registerSummary')
            ->with(
                $namespace,
                $name,
                $description,
                $labels
            )
            ->willReturn($this->randBool());

        $summary = (new Summary($phprom))
            ->setNamespace($namespace)
            ->setName($name)
            ->setDescription($description)
            ->setLabels($labels);

        $summary->register();

        $this->assertTrue($summary->registered());

        $summary->register();
    }

    public function testRecord_success()
    {
        $namespace   = 'test';
        $name        = 'summary';
        $description = 'hotdogs';
        $labels      = ['foo' => 'bar'];
        $value       = $this->randValue();

        $phprom = $this->getMockBuilder(PHProm::class)
            ->disableOriginalConstructor()
            ->getMock();

        $phprom->expects($this->once())
            ->method('registerSummary')
            ->with(
                $namespace,
                $name,
                $description,
                array_keys($labels)
            )
            ->willReturn($this->randBool());

        $phprom->expects($this->once())
            ->method('recordSummary')
            ->with(
                $namespace,
                $name,
                $value,
                $labels
            )
            ->willReturn($this->randBool());

        $summary = (new Summary($phprom))
            ->setNamespace($namespace)
            ->setName($name)
            ->setDescription($description)
            ->setLabels(array_keys($labels));

        $summary->record($value, $labels);

        $this->assertTrue($summary->registered());
    }
}
