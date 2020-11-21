<?php

namespace PHProm\Test;

use Exception;
use Grpc\UnaryCall;
use PHProm\GRPCClient;
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
use stdClass;

class GRPCClientTest extends PHPromTestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    public function testGet_success()
    {
        $request  = new GetRequest();
        $response = new GetResponse();
        $status   = new stdClass();
        $metrics  = 'foo';

        $response->setMetrics($metrics);

        $service = $this->getMockBuilder(ServiceClient::class)
            ->disableOriginalConstructor()
            ->getMock();

        $call = $this->getMockBuilder(UnaryCall::class)
            ->disableOriginalConstructor()
            ->getMock();

        $service->expects($this->once())
            ->method('Get')
            ->with($request)
            ->willReturn($call);

        $call->expects($this->once())
            ->method('wait')
            ->willReturn([$response, $status]);

        $client = new GRPCClient();

        $this->setProtectedProperty($client, 'client', $service);

        $this->assertEquals($response, $client->get($request));
    }

    public function testGet_failure()
    {
        $request  = new GetRequest();
        $response = new GetResponse();
        $status   = new stdClass();
        $code     = rand(1, 10);
        $details  = 'something fucked up';

        $status->code = $code;
        $status->details = $details;

        $service = $this->getMockBuilder(ServiceClient::class)
            ->disableOriginalConstructor()
            ->getMock();

        $call = $this->getMockBuilder(UnaryCall::class)
            ->disableOriginalConstructor()
            ->getMock();

        $service->expects($this->once())
            ->method('Get')
            ->with($request)
            ->willReturn($call);

        $call->expects($this->once())
            ->method('wait')
            ->willReturn([$response, $status]);

        $client = new GRPCClient();

        $this->setProtectedProperty($client, 'client', $service);

        $this->expectException(Exception::class);

        $client->get($request);
    }

    public function testRegisterCounter_success()
    {
        $request  = new RegisterCounterRequest();
        $response = new RegisterResponse();
        $status   = new stdClass();
        $registered  = $this->randBool();

        $response->setRegistered($registered);

        $service = $this->getMockBuilder(ServiceClient::class)
            ->disableOriginalConstructor()
            ->getMock();

        $call = $this->getMockBuilder(UnaryCall::class)
            ->disableOriginalConstructor()
            ->getMock();

        $service->expects($this->once())
            ->method('RegisterCounter')
            ->with($request)
            ->willReturn($call);

        $call->expects($this->once())
            ->method('wait')
            ->willReturn([$response, $status]);

        $client = new GRPCClient();

        $this->setProtectedProperty($client, 'client', $service);

        $this->assertEquals($response, $client->registerCounter($request));
    }

    public function testRegisterHistogram_success()
    {
        $request  = new RegisterHistogramRequest();
        $response = new RegisterResponse();
        $status  = new stdClass();
        $registered  = $this->randBool();

        $response->setRegistered($registered);

        $service = $this->getMockBuilder(ServiceClient::class)
            ->disableOriginalConstructor()
            ->getMock();

        $call = $this->getMockBuilder(UnaryCall::class)
            ->disableOriginalConstructor()
            ->getMock();

        $service->expects($this->once())
            ->method('RegisterHistogram')
            ->with($request)
            ->willReturn($call);

        $call->expects($this->once())
            ->method('wait')
            ->willReturn([$response, $status]);

        $client = new GRPCClient();

        $this->setProtectedProperty($client, 'client', $service);

        $this->assertEquals($response, $client->registerHistogram($request));
    }

    public function testRegisterSummary_success()
    {
        $request  = new RegisterSummaryRequest();
        $response = new RegisterResponse();
        $status  = new stdClass();
        $registered  = $this->randBool();

        $response->setRegistered($registered);

        $service = $this->getMockBuilder(ServiceClient::class)
            ->disableOriginalConstructor()
            ->getMock();

        $call = $this->getMockBuilder(UnaryCall::class)
            ->disableOriginalConstructor()
            ->getMock();

        $service->expects($this->once())
            ->method('RegisterSummary')
            ->with($request)
            ->willReturn($call);

        $call->expects($this->once())
            ->method('wait')
            ->willReturn([$response, $status]);

        $client = new GRPCClient();

        $this->setProtectedProperty($client, 'client', $service);

        $this->assertEquals($response, $client->registerSummary($request));
    }

    public function testRegisterGauge_success()
    {
        $request  = new RegisterGaugeRequest();
        $response = new RegisterResponse();
        $status  = new stdClass();
        $registered  = $this->randBool();

        $response->setRegistered($registered);

        $service = $this->getMockBuilder(ServiceClient::class)
            ->disableOriginalConstructor()
            ->getMock();

        $call = $this->getMockBuilder(UnaryCall::class)
            ->disableOriginalConstructor()
            ->getMock();

        $service->expects($this->once())
            ->method('RegisterGauge')
            ->with($request)
            ->willReturn($call);

        $call->expects($this->once())
            ->method('wait')
            ->willReturn([$response, $status]);

        $client = new GRPCClient();

        $this->setProtectedProperty($client, 'client', $service);

        $this->assertEquals($response, $client->registerGauge($request));
    }

    public function testRecordCounter_success()
    {
        $request  = new RecordCounterRequest();
        $response = new RecordResponse();
        $status   = new stdClass();

        $service = $this->getMockBuilder(ServiceClient::class)
            ->disableOriginalConstructor()
            ->getMock();

        $call = $this->getMockBuilder(UnaryCall::class)
            ->disableOriginalConstructor()
            ->getMock();

        $service->expects($this->once())
            ->method('RecordCounter')
            ->with($request)
            ->willReturn($call);

        $call->expects($this->once())
            ->method('wait')
            ->willReturn([$response, $status]);

        $client = new GRPCClient();

        $this->setProtectedProperty($client, 'client', $service);

        $this->assertEquals($response, $client->recordCounter($request));
    }

    public function testRecordHistogram_success()
    {
        $request  = new RecordHistogramRequest();
        $response = new RecordResponse();
        $status   = new stdClass();

        $service = $this->getMockBuilder(ServiceClient::class)
            ->disableOriginalConstructor()
            ->getMock();

        $call = $this->getMockBuilder(UnaryCall::class)
            ->disableOriginalConstructor()
            ->getMock();

        $service->expects($this->once())
            ->method('RecordHistogram')
            ->with($request)
            ->willReturn($call);

        $call->expects($this->once())
            ->method('wait')
            ->willReturn([$response, $status]);

        $client = new GRPCClient();

        $this->setProtectedProperty($client, 'client', $service);

        $this->assertEquals($response, $client->recordHistogram($request));
    }

    public function testRecordSummary_success()
    {
        $request  = new RecordSummaryRequest();
        $response = new RecordResponse();
        $status   = new stdClass();

        $service = $this->getMockBuilder(ServiceClient::class)
            ->disableOriginalConstructor()
            ->getMock();

        $call = $this->getMockBuilder(UnaryCall::class)
            ->disableOriginalConstructor()
            ->getMock();

        $service->expects($this->once())
            ->method('RecordSummary')
            ->with($request)
            ->willReturn($call);

        $call->expects($this->once())
            ->method('wait')
            ->willReturn([$response, $status]);

        $client = new GRPCClient();

        $this->setProtectedProperty($client, 'client', $service);

        $this->assertEquals($response, $client->recordSummary($request));
    }

    public function testRecordGauge_success()
    {
        $request  = new RecordGaugeRequest();
        $response = new RecordResponse();
        $status   = new stdClass();

        $service = $this->getMockBuilder(ServiceClient::class)
            ->disableOriginalConstructor()
            ->getMock();

        $call = $this->getMockBuilder(UnaryCall::class)
            ->disableOriginalConstructor()
            ->getMock();

        $service->expects($this->once())
            ->method('RecordGauge')
            ->with($request)
            ->willReturn($call);

        $call->expects($this->once())
            ->method('wait')
            ->willReturn([$response, $status]);

        $client = new GRPCClient();

        $this->setProtectedProperty($client, 'client', $service);

        $this->assertEquals($response, $client->recordGauge($request));
    }
}
