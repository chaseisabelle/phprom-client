<?php

namespace PHProm\Test;

use Grpc\UnaryCall;
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
use PHProm\V1\ServiceClient;

final class PHPromTest extends PHPromTestCase
{
    public function testGet_success()
    {
        $metrics = 'foo';

        $response = $this->getMockBuilder(GetResponse::class)
            ->disableOriginalConstructor()
            ->getMock();

        $call = $this->getMockBuilder(UnaryCall::class)
            ->disableOriginalConstructor()
            ->getMock();

        $client = $this->getMockBuilder(ServiceClient::class)
            ->disableOriginalConstructor()
            ->getMock();

        $response->expects($this->once())
            ->method('getMetrics')
            ->willReturn($metrics);

        $call->expects($this->once())
            ->method('wait')
            ->willReturn([$response, null]);

        $client->expects($this->once())
            ->method('Get')
            ->with($this->isInstanceOf(GetRequest::class))
            ->willReturn($call);

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

        $response = $this->getMockBuilder(RegisterResponse::class)
            ->disableOriginalConstructor()
            ->getMock();

        $call = $this->getMockBuilder(UnaryCall::class)
            ->disableOriginalConstructor()
            ->getMock();

        $client = $this->getMockBuilder(ServiceClient::class)
            ->disableOriginalConstructor()
            ->getMock();

        $response->expects($this->once())
            ->method('getRegistered')
            ->willReturn($registered);

        $call->expects($this->once())
            ->method('wait')
            ->willReturn([$response, null]);

        $client->expects($this->once())
            ->method('RegisterCounter')
            ->with($this->isInstanceOf(RegisterCounterRequest::class))
            ->willReturn($call);

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

        $response = $this->getMockBuilder(RegisterResponse::class)
            ->disableOriginalConstructor()
            ->getMock();

        $call = $this->getMockBuilder(UnaryCall::class)
            ->disableOriginalConstructor()
            ->getMock();

        $client = $this->getMockBuilder(ServiceClient::class)
            ->disableOriginalConstructor()
            ->getMock();

        $response->expects($this->once())
            ->method('getRegistered')
            ->willReturn($registered);

        $call->expects($this->once())
            ->method('wait')
            ->willReturn([$response, null]);

        $client->expects($this->once())
            ->method('RegisterHistogram')
            ->with($this->isInstanceOf(RegisterHistogramRequest::class))
            ->willReturn($call);

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

        $response = $this->getMockBuilder(RegisterResponse::class)
            ->disableOriginalConstructor()
            ->getMock();

        $call = $this->getMockBuilder(UnaryCall::class)
            ->disableOriginalConstructor()
            ->getMock();

        $client = $this->getMockBuilder(ServiceClient::class)
            ->disableOriginalConstructor()
            ->getMock();

        $response->expects($this->once())
            ->method('getRegistered')
            ->willReturn($registered);

        $call->expects($this->once())
            ->method('wait')
            ->willReturn([$response, null]);

        $client->expects($this->once())
            ->method('RegisterSummary')
            ->with($this->isInstanceOf(RegisterSummaryRequest::class))
            ->willReturn($call);

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

        $response = $this->getMockBuilder(RegisterResponse::class)
            ->disableOriginalConstructor()
            ->getMock();

        $call = $this->getMockBuilder(UnaryCall::class)
            ->disableOriginalConstructor()
            ->getMock();

        $client = $this->getMockBuilder(ServiceClient::class)
            ->disableOriginalConstructor()
            ->getMock();

        $response->expects($this->once())
            ->method('getRegistered')
            ->willReturn($registered);

        $call->expects($this->once())
            ->method('wait')
            ->willReturn([$response, null]);

        $client->expects($this->once())
            ->method('RegisterGauge')
            ->with($this->isInstanceOf(RegisterGaugeRequest::class))
            ->willReturn($call);

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

        $response = $this->getMockBuilder(RecordResponse::class)
            ->disableOriginalConstructor()
            ->getMock();

        $call = $this->getMockBuilder(UnaryCall::class)
            ->disableOriginalConstructor()
            ->getMock();

        $client = $this->getMockBuilder(ServiceClient::class)
            ->disableOriginalConstructor()
            ->getMock();

        $call->expects($this->once())
            ->method('wait')
            ->willReturn([$response, null]);

        $client->expects($this->once())
            ->method('RecordCounter')
            ->with($this->isInstanceOf(RecordCounterRequest::class))
            ->willReturn($call);

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

        $response = $this->getMockBuilder(RecordResponse::class)
            ->disableOriginalConstructor()
            ->getMock();

        $call = $this->getMockBuilder(UnaryCall::class)
            ->disableOriginalConstructor()
            ->getMock();

        $client = $this->getMockBuilder(ServiceClient::class)
            ->disableOriginalConstructor()
            ->getMock();

        $call->expects($this->once())
            ->method('wait')
            ->willReturn([$response, null]);

        $client->expects($this->once())
            ->method('RecordHistogram')
            ->with($this->isInstanceOf(RecordHistogramRequest::class))
            ->willReturn($call);

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

        $response = $this->getMockBuilder(RecordResponse::class)
            ->disableOriginalConstructor()
            ->getMock();

        $call = $this->getMockBuilder(UnaryCall::class)
            ->disableOriginalConstructor()
            ->getMock();

        $client = $this->getMockBuilder(ServiceClient::class)
            ->disableOriginalConstructor()
            ->getMock();

        $call->expects($this->once())
            ->method('wait')
            ->willReturn([$response, null]);

        $client->expects($this->once())
            ->method('RecordSummary')
            ->with($this->isInstanceOf(RecordSummaryRequest::class))
            ->willReturn($call);

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

        $response = $this->getMockBuilder(RecordResponse::class)
            ->disableOriginalConstructor()
            ->getMock();

        $call = $this->getMockBuilder(UnaryCall::class)
            ->disableOriginalConstructor()
            ->getMock();

        $client = $this->getMockBuilder(ServiceClient::class)
            ->disableOriginalConstructor()
            ->getMock();

        $call->expects($this->once())
            ->method('wait')
            ->willReturn([$response, null]);

        $client->expects($this->once())
            ->method('RecordGauge')
            ->with($this->isInstanceOf(RecordGaugeRequest::class))
            ->willReturn($call);

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
