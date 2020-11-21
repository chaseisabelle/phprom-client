<?php

namespace PHProm\Test;

use Grpc\UnaryCall;
use PHProm\Client;
use PHProm\PHProm;
use PHProm\V1\GetRequest;
use PHProm\V1\GetResponse;
use PHProm\V1\RecordCounterRequest;
use PHProm\V1\RecordGaugeRequest;
use PHProm\V1\RecordHistogramRequest;
use PHProm\V1\RecordResponse;
use PHProm\V1\RecordSummaryRequest;
use PHProm\V1\RegisterCounterRequest;
use PHProm\V1\RegisterGaugeRequest;
use PHProm\V1\RegisterHistogramRequest;
use PHProm\V1\RegisterResponse;
use PHProm\V1\RegisterSummaryRequest;

final class PHPromTest extends PHPromTestCase
{
    public function testGet_success()
    {
        $metrics = 'foo';

        $response = $this->getMockBuilder(GetResponse::class)
            ->disableOriginalConstructor()
            ->getMock();

        $client = $this->getMockBuilder(Client::class)
            ->disableOriginalConstructor()
            ->getMock();

        $response->expects($this->once())
            ->method('getMetrics')
            ->willReturn($metrics);

        $client->expects($this->once())
            ->method('get')
            ->with($this->isInstanceOf(GetRequest::class))
            ->willReturn($response);

        $phprom = new PHProm();

        $this->setProtectedProperty($phprom, 'client', $client);
        $this->assertEquals($metrics, $phprom->get());
    }

    public function testRegisterCounter_success()
    {
        $namespace   = 'test';
        $name        = 'counter';
        $description = 'stone cold steve austin with a stunner';
        $labels      = ['foo'];
        $registered  = $this->randBool();
        $test        = $this;

        $response = $this->getMockBuilder(RegisterResponse::class)
            ->disableOriginalConstructor()
            ->getMock();

        $client = $this->getMockBuilder(Client::class)
            ->disableOriginalConstructor()
            ->getMock();

        $response->expects($this->once())
            ->method('getRegistered')
            ->willReturn($registered);

        $client->expects($this->once())
            ->method('registerCounter')
            ->with($this->isInstanceOf(RegisterCounterRequest::class))
            ->willReturnCallback(function (RegisterCounterRequest $request) use (
                $test,
                $namespace,
                $name,
                $description,
                $labels,
                $response
            ) {
                $test->assertEquals($namespace, $request->getNamespace());
                $test->assertEquals($name, $request->getName());
                $test->assertEquals($description, $request->getDescription());

                foreach ($request->getLabels() as $index => $label) {
                    $test->assertEquals($labels[$index], $label);
                }

                return $response;
            });

        $phprom = new PHProm();

        $this->setProtectedProperty($phprom, 'client', $client);

        $this->assertEquals($registered, $phprom->registerCounter(
            $namespace,
            $name,
            $description,
            $labels
        ));
    }

    public function testRegisterHistogram_success()
    {
        $namespace   = 'test';
        $name        = 'histo';
        $description = 'stone cold steve austin with a stunner';
        $labels      = ['foo'];
        $buckets     = [1, 2, 3];
        $registered  = $this->randBool();
        $test        = $this;

        $response = $this->getMockBuilder(RegisterResponse::class)
            ->disableOriginalConstructor()
            ->getMock();

        $client = $this->getMockBuilder(Client::class)
            ->disableOriginalConstructor()
            ->getMock();

        $response->expects($this->once())
            ->method('getRegistered')
            ->willReturn($registered);

        $client->expects($this->once())
            ->method('RegisterHistogram')
            ->with($this->isInstanceOf(RegisterHistogramRequest::class))
            ->willReturnCallback(function (RegisterHistogramRequest $request) use (
                $test,
                $namespace,
                $name,
                $description,
                $labels,
                $buckets,
                $response
            ) {
                $test->assertEquals($namespace, $request->getNamespace());
                $test->assertEquals($name, $request->getName());
                $test->assertEquals($description, $request->getDescription());

                foreach ($request->getLabels() as $index => $label) {
                    $test->assertEquals($labels[$index], $label);
                }

                foreach ($request->getBuckets() as $index => $bucket) {
                    $test->assertEquals($buckets[$index], $bucket);
                }

                return $response;
            });

        $phprom = new PHProm();

        $this->setProtectedProperty($phprom, 'client', $client);

        $this->assertEquals($registered, $phprom->registerHistogram(
            $namespace,
            $name,
            $description,
            $labels,
            $buckets
        ));
    }

    public function testRegisterSummary_success()
    {
        $namespace   = 'test';
        $name        = 'summary';
        $description = 'stone cold steve austin with a stunner';
        $labels      = ['foo'];
        $registered  = $this->randBool();
        $test = $this;

        $response = $this->getMockBuilder(RegisterResponse::class)
            ->disableOriginalConstructor()
            ->getMock();

        $client = $this->getMockBuilder(Client::class)
            ->disableOriginalConstructor()
            ->getMock();

        $response->expects($this->once())
            ->method('getRegistered')
            ->willReturn($registered);

        $client->expects($this->once())
            ->method('registerSummary')
            ->with($this->isInstanceOf(RegisterSummaryRequest::class))
            ->willReturnCallback(function (RegisterSummaryRequest $request) use (
                $test,
                $namespace,
                $name,
                $description,
                $labels,
                $response
            ) {
                $test->assertEquals($namespace, $request->getNamespace());
                $test->assertEquals($name, $request->getName());
                $test->assertEquals($description, $request->getDescription());

                foreach ($request->getLabels() as $index => $label) {
                    $test->assertEquals($labels[$index], $label);
                }

                return $response;
            });

        $phprom = new PHProm();

        $this->setProtectedProperty($phprom, 'client', $client);

        $this->assertEquals($registered, $phprom->registerSummary(
            $namespace,
            $name,
            $description,
            $labels
        ));
    }

    public function testRegisterGauge_success()
    {
        $namespace   = 'test';
        $name        = 'gauge';
        $description = 'stone cold steve austin with a stunner';
        $labels      = ['foo'];
        $registered  = $this->randBool();
        $test = $this;

        $response = $this->getMockBuilder(RegisterResponse::class)
            ->disableOriginalConstructor()
            ->getMock();

        $client = $this->getMockBuilder(Client::class)
            ->disableOriginalConstructor()
            ->getMock();

        $response->expects($this->once())
            ->method('getRegistered')
            ->willReturn($registered);

        $client->expects($this->once())
            ->method('registerGauge')
            ->with($this->isInstanceOf(RegisterGaugeRequest::class))
            ->willReturnCallback(function (RegisterGaugeRequest $request) use (
                $test,
                $namespace,
                $name,
                $description,
                $labels,
                $response
            ) {
                $test->assertEquals($namespace, $request->getNamespace());
                $test->assertEquals($name, $request->getName());
                $test->assertEquals($description, $request->getDescription());

                foreach ($request->getLabels() as $index => $label) {
                    $test->assertEquals($labels[$index], $label);
                }

                return $response;
            });

        $phprom = new PHProm();

        $this->setProtectedProperty($phprom, 'client', $client);

        $this->assertEquals($registered, $phprom->registerGauge(
            $namespace,
            $name,
            $description,
            $labels
        ));
    }

    public function testRecordCounter_success()
    {
        $namespace = 'test';
        $name      = 'counter';
        $value     = $this->randValue();
        $labels    = ['bar'];
        $test = $this;

        $response = $this->getMockBuilder(RecordResponse::class)
            ->disableOriginalConstructor()
            ->getMock();

        $client = $this->getMockBuilder(Client::class)
            ->disableOriginalConstructor()
            ->getMock();

        $client->expects($this->once())
            ->method('recordCounter')
            ->with($this->isInstanceOf(RecordCounterRequest::class))
            ->willReturnCallback(function (RecordCounterRequest $request) use (
                $test,
                $namespace,
                $name,
                $value,
                $labels,
                $response
            ) {
                $test->assertEquals($namespace, $request->getNamespace());
                $test->assertEquals($name, $request->getName());
                $test->assertEquals($value, $request->getValue());

                foreach ($request->getLabels() as $index => $label) {
                    $test->assertEquals($labels[$index], $label);
                }

                return $response;
            });

        $phprom = new PHProm();

        $this->setProtectedProperty($phprom, 'client', $client);

        $phprom->recordCounter(
            $namespace,
            $name,
            $value,
            $labels
        );
    }

    public function testRecordHistogram_success()
    {
        $namespace = 'test';
        $name      = 'histo';
        $value     = $this->randValue();
        $labels    = ['bar'];
        $test = $this;

        $response = $this->getMockBuilder(RecordResponse::class)
            ->disableOriginalConstructor()
            ->getMock();

        $client = $this->getMockBuilder(Client::class)
            ->disableOriginalConstructor()
            ->getMock();

        $client->expects($this->once())
            ->method('RecordHistogram')
            ->with($this->isInstanceOf(RecordHistogramRequest::class))
            ->willReturnCallback(function (RecordHistogramRequest $request) use (
                $test,
                $namespace,
                $name,
                $value,
                $labels,
                $response
            ) {
                $test->assertEquals($namespace, $request->getNamespace());
                $test->assertEquals($name, $request->getName());
                $test->assertEquals($value, $request->getValue());

                foreach ($request->getLabels() as $index => $label) {
                    $test->assertEquals($labels[$index], $label);
                }

                return $response;
            });

        $phprom = new PHProm();

        $this->setProtectedProperty($phprom, 'client', $client);

        $phprom->recordHistogram(
            $namespace,
            $name,
            $value,
            $labels
        );
    }

    public function testRecordSummary_success()
    {
        $namespace = 'test';
        $name      = 'summary';
        $value     = $this->randValue();
        $labels    = ['bar'];
        $test = $this;

        $response = $this->getMockBuilder(RecordResponse::class)
            ->disableOriginalConstructor()
            ->getMock();

        $client = $this->getMockBuilder(Client::class)
            ->disableOriginalConstructor()
            ->getMock();

        $client->expects($this->once())
            ->method('recordSummary')
            ->with($this->isInstanceOf(RecordSummaryRequest::class))
            ->willReturnCallback(function (RecordSummaryRequest $request) use (
                $test,
                $namespace,
                $name,
                $value,
                $labels,
                $response
            ) {
                $test->assertEquals($namespace, $request->getNamespace());
                $test->assertEquals($name, $request->getName());
                $test->assertEquals($value, $request->getValue());

                foreach ($request->getLabels() as $index => $label) {
                    $test->assertEquals($labels[$index], $label);
                }

                return $response;
            });

        $phprom = new PHProm();

        $this->setProtectedProperty($phprom, 'client', $client);

        $phprom->recordSummary(
            $namespace,
            $name,
            $value,
            $labels
        );
    }

    public function testRecordGauge_success()
    {
        $namespace = 'test';
        $name      = 'gauge';
        $value     = $this->randValue();
        $labels    = ['bar'];
        $test = $this;

        $response = $this->getMockBuilder(RecordResponse::class)
            ->disableOriginalConstructor()
            ->getMock();

        $client = $this->getMockBuilder(Client::class)
            ->disableOriginalConstructor()
            ->getMock();

        $client->expects($this->once())
            ->method('recordGauge')
            ->with($this->isInstanceOf(RecordGaugeRequest::class))
            ->willReturnCallback(function (RecordGaugeRequest $request) use (
                $test,
                $namespace,
                $name,
                $value,
                $labels,
                $response
            ) {
                $test->assertEquals($namespace, $request->getNamespace());
                $test->assertEquals($name, $request->getName());
                $test->assertEquals($value, $request->getValue());

                foreach ($request->getLabels() as $index => $label) {
                    $test->assertEquals($labels[$index], $label);
                }

                return $response;
            });

        $phprom = new PHProm();

        $this->setProtectedProperty($phprom, 'client', $client);

        $phprom->recordGauge(
            $namespace,
            $name,
            $value,
            $labels
        );
    }
}
