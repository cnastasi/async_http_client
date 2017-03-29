<?php
/**
 * Created by PhpStorm.
 * User: christian
 * Date: 12/26/16
 * Time: 1:40 PM
 */

namespace AsyncHttpClient\Core;


use AsyncHttpClient\Logger\AsyncHttpLogger;
use AsyncHttpClient\Service\AsyncHttpService;
use Mockery;
use React\EventLoop\LoopInterface;
use React\HttpClient\Client;
use React\HttpClient\Request;
use React\HttpClient\Response;

class AsyncHttpClientDefaultTest extends \PHPUnit_Framework_TestCase
{
    public function testGET()
    {
        $method             = 'GET';
        $url                = 'RandomUrl';
        $expectedHeaders    = [];

        $reactClient = $this->mockReactClient($method, $url, $expectedHeaders);
        $eventLoop   = $this->mockEventLoop();
        $logger      = $this->mockLogger();
        $service     = $this->mockService($method, $url, $expectedHeaders);

        $asyncClient = new AsyncHttpClientDefault($reactClient, $eventLoop, $logger);

        $asyncClient->addService($service);

        $asyncClient->send();

        Mockery::close();
    }

    /**
     * @return Mockery\MockInterface|Client
     */
    private function mockReactClient($method, $url, $headers)
    {
        $mock = Mockery::mock(Client::class);

        $request = $this->mockRequest('aa');

        $mock->shouldReceive('request')->with($method, $url, $headers)->andReturn($request);

        return $mock;
    }

    /**
     * @return Mockery\MockInterface|LoopInterface
     */
    private function mockEventLoop()
    {
        $mock = Mockery::mock(LoopInterface::class);

        $mock->shouldReceive('run')->once();

        return $mock;
    }

    /**
     * @return Mockery\MockInterface|AsyncHttpLogger
     */
    private function mockLogger()
    {
        $mock = Mockery::mock(AsyncHttpLogger::class);

        $mock->shouldReceive('logTotal')->once();
        $mock->shouldReceive('log')->once();

        return $mock;
    }

    /**
     * @return Mockery\MockInterface|AsyncHttpService
     */
    private function mockService($method, $url, $headers)
    {
        $mock = Mockery::mock(AsyncHttpService::class);

        $mock->shouldReceive('getMethod')->twice()->andReturn($method);
        $mock->shouldReceive('getUrl')->twice()->andReturn($url);
        $mock->shouldReceive('getHeaders')->once()->andReturn($headers);
        $mock->shouldReceive('getContent')->once();
        $mock->shouldReceive('execute')->once();
        
        
        return $mock;
    }

    /**
     * @return Mockery\MockInterface|Request
     */
    private function mockRequest($data)
    {
        $mock = Mockery::mock(Request::class);

        $callbackTest = function ($callback) {
            return false;
        };

        $mock->shouldReceive('on')->withArgs(function ($event, $closure) use ($data) {
            if ($event !== 'response') {
                return false;
            }

            $response = $this->mockResponse ();

            $response->shouldReceive('on')->withArgs(function ($event, $closure) use ($data, $response) {
                if ($event !== 'data' && $event != 'error') {
                    return false;
                }

                $closure($data, $response);

                return true;
            });

            $closure($response);

            return true;
        }
        );
        
        $mock->shouldReceive('write')->once();

        $mock->shouldReceive('end')->once();

        return $mock;
    }

    private function mockResponse() {
        $mock = Mockery::mock(Response::class);

        return $mock;
    }
}
