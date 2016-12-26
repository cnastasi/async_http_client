<?php
/**
 * Created by PhpStorm.
 * User: christian
 * Date: 12/26/16
 * Time: 1:40 PM
 */

namespace AsyncHttpClient\Core;


use AsyncHttpClient\Logger\AsyncHttpLogger;
use Mockery;
use React\EventLoop\LoopInterface;
use React\HttpClient\Client;
use React\HttpClient\Request;

class AsyncHttpClientDefaultTest extends \PHPUnit_Framework_TestCase
{
    public function test()
    {
        $method = 'GET';
        $url    = 'RandomUrl';

        $reactClient = $this->mockReactClient($method, $url);
        $eventLoop   = $this->mockEventLoop();
        $logger      = $this->mockLogger();
        $service     = $this->mockService($method, $url);

        $asyncClient = new AsyncHttpClientDefault($reactClient, $eventLoop, $logger);

        $asyncClient->addService($service);

        $asyncClient->send();
    }

    /**
     * @return Mockery\MockInterface|Client
     */
    private function mockReactClient($method, $url)
    {
        $mock = Mockery::mock(Client::class);

        $request = $this->mockRequest();

        $mock->shouldReceive('request')->with($method, $url)->andReturn($request);

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

        return $mock;
    }

    /**
     * @return Mockery\MockInterface|AsyncHttpService
     */
    private function mockService($method, $url)
    {
        $mock = Mockery::mock(AsyncHttpService::class);

        $mock->shouldReceive('getMethod')->once()->andReturn($method);
        $mock->shouldReceive('getUrl')->once()->andReturn($url);

        return $mock;
    }

    /**
     * @return Mockery\MockInterface|Request
     */
    private function mockRequest()
    {
        $mock = Mockery::mock(Request::class);

        $callbackTest = function ($callback) {
            return false;
        };

        $mock->shouldReceive(['on'=>function () {
            return count(func_get_args()) == 3;
        }]);

        $mock->shouldReceive('end')->once();

        return $mock;
    }
}
