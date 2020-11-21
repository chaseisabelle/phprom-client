<?php

namespace PHProm\Test;

use Exception;
use Grpc\UnaryCall;
use PHProm\GRPCClient;
use PHProm\RESTClient;
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

class RESTClientTest extends PHPromTestCase
{
    public function testAddress_success()
    {
        $address = '0.0.0.0:3333';
        $client  = new RESTClient($address);

        $this->assertEquals("http://$address", $client->address());
    }

    public function testGet_success()
    {
        $request  = new GetRequest();
        $response = new GetResponse();
        $metrics  = 'foo';

        $response->setMetrics($metrics);

        $client = new class ($this, $metrics) extends RESTClient {
            private $test;
            private $metrics;

            public function __construct($test, $metrics)
            {
                parent::__construct();

                $this->test    = $test;
                $this->metrics = $metrics;
            }

            protected function curl(string $url, string $method, string $body, bool $decode = true)
            {
                $this->test->assertEquals($url, $this->address() . '/metrics');
                $this->test->assertEquals($method, 'GET');
                $this->test->assertEquals($body, '{}');
                $this->test->assertFalse($decode);

                return $this->metrics;
            }
        };

        $this->assertEquals($response, $client->get($request));
    }

    public function testRegisterCounter_success()
    {
        $namespace   = 'test';
        $name        = 'counter';
        $description = 'poopoo peepee';
        $labels      = ['caca'];
        $request     = new RegisterCounterRequest();
        $response    = new RegisterResponse();
        $registered  = $this->randBool();

        $request->setNamespace($namespace)
            ->setName($name)
            ->setDescription($description)
            ->setLabels($labels);

        $response->setRegistered($registered);

        $client = new class ($this, $request, $response) extends RESTClient {
            private $test;
            private $request;
            private $response;

            public function __construct($test, $request, $response)
            {
                parent::__construct();

                $this->test     = $test;
                $this->request  = $request;
                $this->response = $response;
            }

            protected function curl(string $url, string $method, string $body, bool $decode = true)
            {
                $this->test->assertEquals($url, $this->address() . '/register/counter');
                $this->test->assertEquals($method, 'POST');
                $this->test->assertEquals($body, $this->request->serializeToJsonString());
                $this->test->assertTrue($decode);

                return ['registered' => $this->response->getRegistered()];
            }
        };

        $this->assertEquals($response, $client->registerCounter($request));
    }

    public function testRegisterCounter_emptyResponse()
    {
        $namespace   = 'test';
        $name        = 'counter';
        $description = 'poopoo peepee';
        $labels      = ['caca'];
        $request     = new RegisterCounterRequest();
        $response    = new RegisterResponse();

        $request->setNamespace($namespace)
            ->setName($name)
            ->setDescription($description)
            ->setLabels($labels);

        $client = new class ($this, $request, $response) extends RESTClient {
            private $test;
            private $request;
            private $response;

            public function __construct($test, $request, $response)
            {
                parent::__construct();

                $this->test     = $test;
                $this->request  = $request;
                $this->response = $response;
            }

            protected function curl(string $url, string $method, string $body, bool $decode = true)
            {
                $this->test->assertEquals($url, $this->address() . '/register/counter');
                $this->test->assertEquals($method, 'POST');
                $this->test->assertEquals($body, $this->request->serializeToJsonString());
                $this->test->assertTrue($decode);

                return [];
            }
        };

        $this->assertEquals($response, $client->registerCounter($request));
    }

    public function testRegisterHistogram_success()
    {
        $namespace   = 'test';
        $name        = 'histogram';
        $description = 'poopoo peepee';
        $labels      = ['caca'];
        $buckets     = range(1, 10);
        $request    = new RegisterHistogramRequest();
        $response   = new RegisterResponse();
        $registered = $this->randBool();

        $request->setNamespace($namespace)
            ->setName($name)
            ->setDescription($description)
            ->setLabels($labels)
            ->setBuckets($buckets);

        $response->setRegistered($registered);

        $client = new class ($this, $request, $response) extends RESTClient {
            private $test;
            private $request;
            private $response;

            public function __construct($test, $request, $response)
            {
                parent::__construct();

                $this->test     = $test;
                $this->request  = $request;
                $this->response = $response;
            }

            protected function curl(string $url, string $method, string $body, bool $decode = true)
            {
                $this->test->assertEquals($url, $this->address() . '/register/histogram');
                $this->test->assertEquals($method, 'POST');
                $this->test->assertEquals($body, $this->request->serializeToJsonString());
                $this->test->assertTrue($decode);

                return ['registered' => $this->response->getRegistered()];
            }
        };

        $this->assertEquals($response, $client->registerHistogram($request));
    }

    public function testRegisterSummary_success()
    {
        $namespace   = 'test';
        $name        = 'summary';
        $description = 'poopoo peepee';
        $labels      = ['caca'];
        $request    = new RegisterSummaryRequest();
        $response   = new RegisterResponse();
        $registered = $this->randBool();

        $request->setNamespace($namespace)
            ->setName($name)
            ->setDescription($description)
            ->setLabels($labels);

        $response->setRegistered($registered);

        $client = new class ($this, $request, $response) extends RESTClient {
            private $test;
            private $request;
            private $response;

            public function __construct($test, $request, $response)
            {
                parent::__construct();

                $this->test     = $test;
                $this->request  = $request;
                $this->response = $response;
            }

            protected function curl(string $url, string $method, string $body, bool $decode = true)
            {
                $this->test->assertEquals($url, $this->address() . '/register/summary');
                $this->test->assertEquals($method, 'POST');
                $this->test->assertEquals($body, $this->request->serializeToJsonString());
                $this->test->assertTrue($decode);

                return ['registered' => $this->response->getRegistered()];
            }
        };

        $this->assertEquals($response, $client->registerSummary($request));
    }

    public function testRegisterGauge_success()
    {
        $namespace   = 'test';
        $name        = 'gauge';
        $description = 'poopoo peepee';
        $labels      = ['caca'];
        $request    = new RegisterGaugeRequest();
        $response   = new RegisterResponse();
        $registered = $this->randBool();

        $request->setNamespace($namespace)
            ->setName($name)
            ->setDescription($description)
            ->setLabels($labels);

        $response->setRegistered($registered);

        $client = new class ($this, $request, $response) extends RESTClient {
            private $test;
            private $request;
            private $response;

            public function __construct($test, $request, $response)
            {
                parent::__construct();

                $this->test     = $test;
                $this->request  = $request;
                $this->response = $response;
            }

            protected function curl(string $url, string $method, string $body, bool $decode = true)
            {
                $this->test->assertEquals($url, $this->address() . '/register/gauge');
                $this->test->assertEquals($method, 'POST');
                $this->test->assertEquals($body, $this->request->serializeToJsonString());
                $this->test->assertTrue($decode);

                return ['registered' => $this->response->getRegistered()];
            }
        };

        $this->assertEquals($response, $client->registerGauge($request));
    }

    public function testRecordCounter_success()
    {
        $namespace   = 'test';
        $name        = 'counter';
        $value     = rand(1, 999);
        $labels      = ['caca' => 'peepee'];
        $request    = new RecordCounterRequest();
        $response   = new RecordResponse();

        $request->setNamespace($namespace)
            ->setName($name)
            ->setValue($value)
            ->setLabels($labels);

        $client = new class ($this, $request, $response) extends RESTClient {
            private $test;
            private $request;
            private $response;

            public function __construct($test, $request, $response)
            {
                parent::__construct();

                $this->test     = $test;
                $this->request  = $request;
                $this->response = $response;
            }

            protected function curl(string $url, string $method, string $body, bool $decode = true)
            {
                $this->test->assertEquals($url, $this->address() . '/record/counter');
                $this->test->assertEquals($method, 'POST');
                $this->test->assertEquals($body, $this->request->serializeToJsonString());
                $this->test->assertFalse($decode);

                return '';
            }
        };

        $this->assertEquals($response, $client->recordCounter($request));
    }

    public function testRecordHistogram_success()
    {
        $namespace   = 'test';
        $name        = 'histogram';
        $value     = rand(1, 999);
        $labels      = ['caca' => 'peepee'];
        $request    = new RecordHistogramRequest();
        $response   = new RecordResponse();

        $request->setNamespace($namespace)
            ->setName($name)
            ->setValue($value)
            ->setLabels($labels);

        $client = new class ($this, $request, $response) extends RESTClient {
            private $test;
            private $request;
            private $response;

            public function __construct($test, $request, $response)
            {
                parent::__construct();

                $this->test     = $test;
                $this->request  = $request;
                $this->response = $response;
            }

            protected function curl(string $url, string $method, string $body, bool $decode = true)
            {
                $this->test->assertEquals($url, $this->address() . '/record/histogram');
                $this->test->assertEquals($method, 'POST');
                $this->test->assertEquals($body, $this->request->serializeToJsonString());
                $this->test->assertFalse($decode);

                return '';
            }
        };

        $this->assertEquals($response, $client->recordHistogram($request));
    }

    public function testRecordSummary_success()
    {
        $namespace   = 'test';
        $name        = 'summary';
        $value     = rand(1, 999);
        $labels      = ['caca' => 'peepee'];
        $request    = new RecordSummaryRequest();
        $response   = new RecordResponse();

        $request->setNamespace($namespace)
            ->setName($name)
            ->setValue($value)
            ->setLabels($labels);

        $client = new class ($this, $request, $response) extends RESTClient {
            private $test;
            private $request;
            private $response;

            public function __construct($test, $request, $response)
            {
                parent::__construct();

                $this->test     = $test;
                $this->request  = $request;
                $this->response = $response;
            }

            protected function curl(string $url, string $method, string $body, bool $decode = true)
            {
                $this->test->assertEquals($url, $this->address() . '/record/summary');
                $this->test->assertEquals($method, 'POST');
                $this->test->assertEquals($body, $this->request->serializeToJsonString());
                $this->test->assertFalse($decode);

                return '';
            }
        };

        $this->assertEquals($response, $client->recordSummary($request));
    }

    public function testRecordGauge_success()
    {
        $namespace   = 'test';
        $name        = 'counter';
        $value     = rand(1, 999);
        $labels      = ['caca' => 'peepee'];
        $request    = new RecordGaugeRequest();
        $response   = new RecordResponse();

        $request->setNamespace($namespace)
            ->setName($name)
            ->setValue($value)
            ->setLabels($labels);

        $client = new class ($this, $request, $response) extends RESTClient {
            private $test;
            private $request;
            private $response;

            public function __construct($test, $request, $response)
            {
                parent::__construct();

                $this->test     = $test;
                $this->request  = $request;
                $this->response = $response;
            }

            protected function curl(string $url, string $method, string $body, bool $decode = true)
            {
                $this->test->assertEquals($url, $this->address() . '/record/gauge');
                $this->test->assertEquals($method, 'POST');
                $this->test->assertEquals($body, $this->request->serializeToJsonString());
                $this->test->assertFalse($decode);

                return '';
            }
        };

        $this->assertEquals($response, $client->recordGauge($request));
    }
}
