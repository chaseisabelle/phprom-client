<?php

namespace PHProm\Test;

use PHProm\Histogram;
use PHProm\Timer;

class TimerTest extends PHPromTestCase
{
    public function testFull_success()
    {
        $labels = ['foo' => 'bar'];

        $histogram = $this->getMockBuilder(Histogram::class)
            ->disableOriginalConstructor()
            ->getMock();

        $histogram->expects($this->once())
            ->method('record')
            ->with($this->isType('float'), $labels);

        $timer = new Timer($histogram);

        $timer->start();

        usleep(100);

        $timer->stop()->record($labels);
    }
}
