<?php

namespace PHProm\Test;

use PHProm\Counter;
use PHProm\Histogram;
use PHProm\PHProm;

class HistogramTest extends PHPromTestCase
{
    public function testRegister_success()
    {
        $namespace   = 'test';
        $name        = 'histo';
        $description = 'hotdogs';
        $labels      = ['foo'];
        $buckets     = range(1, 10);

        $phprom = $this->getMockBuilder(PHProm::class)
            ->disableOriginalConstructor()
            ->getMock();

        $phprom->expects($this->once())
            ->method('registerHistogram')
            ->with(
                $namespace,
                $name,
                $description,
                $labels,
                $buckets
            )
            ->willReturn($this->randBool());

        $histogram = (new Histogram($phprom))
            ->setNamespace($namespace)
            ->setName($name)
            ->setDescription($description)
            ->setLabels($labels)
            ->setBuckets($buckets);

        $histogram->register();

        $this->assertTrue($histogram->registered());

        $histogram->register();
    }

    public function testRecord_success()
    {
        $namespace   = 'test';
        $name        = 'histo';
        $description = 'hotdogs';
        $labels      = ['foo' => 'bar'];
        $buckets     = range(1, 10);
        $value       = $this->randValue();

        $phprom = $this->getMockBuilder(PHProm::class)
            ->disableOriginalConstructor()
            ->getMock();

        $phprom->expects($this->once())
            ->method('registerHistogram')
            ->with(
                $namespace,
                $name,
                $description,
                array_keys($labels),
                $buckets
            )
            ->willReturn($this->randBool());

        $phprom->expects($this->once())
            ->method('recordHistogram')
            ->with(
                $namespace,
                $name,
                $value,
                $labels
            )
            ->willReturn($this->randBool());

        $histogram = (new Histogram($phprom))
            ->setNamespace($namespace)
            ->setName($name)
            ->setDescription($description)
            ->setLabels(array_keys($labels))
            ->setBuckets($buckets);

        $histogram->record($value, $labels);

        $this->assertTrue($histogram->registered());
    }
}
