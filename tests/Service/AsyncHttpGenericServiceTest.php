<?php
/**
 * Created by PhpStorm.
 * User: christian
 * Date: 12/28/16
 * Time: 12:52 AM
 */

namespace Service;

use AsyncHttpClient\Service\AsyncHttpGenericService;
use Mockery\Adapter\Phpunit\MockeryTestCase;
use PHPUnit_Framework_Assert as PHPUnit;
use React\HttpClient\Response;

class AsyncHttpGenericServiceTest extends MockeryTestCase
{
    public function testPOST()
    {
        $method   = 'POST';
        $url      = 'http://www.google.it';
        $content  = 'field1=1&field2=2';
        $called   = false;
        $data     = '{ "result": true }';
        $response = $this->mockResponse();
        $expectedHeaders    = [
            'Content-Length' => strlen($content),
            'Content-Type' => 'application/x-www-form-urlencoded'
        ];

        $callback = function ($d, Response $r) use (&$called, $data, $response) {
            $called = true;

            PHPUnit::assertEquals($data, $d);
            PHPUnit::assertEquals($response, $r);
        };

        $service = new AsyncHttpGenericService($method, $url, $content, $callback);

        $service->execute($data, $response);

        PHPUnit::assertTrue($called);
        PHPUnit::assertEquals($method, $service->getMethod());
        PHPUnit::assertEquals($url, $service->getUrl());
        PHPUnit::assertEquals($content, $service->getContent());
        PHPUnit::assertEquals($expectedHeaders, $service->getHeaders());
    }
    
    public function testGET()
    {
        $method   = 'GET';
        $url      = 'http://www.google.it';
        $content  = null;
        $called   = false;
        $data     = '{ "result": true }';
        $response = $this->mockResponse();
        $expectedHeaders    = [];
    
        $callback = function ($d, Response $r) use (&$called, $data, $response) {
            $called = true;
        
            PHPUnit::assertEquals($data, $d);
            PHPUnit::assertEquals($response, $r);
        };
    
        $service = new AsyncHttpGenericService($method, $url, $content, $callback);
    
        $service->execute($data, $response);
    
        PHPUnit::assertTrue($called);
        PHPUnit::assertEquals($method, $service->getMethod());
        PHPUnit::assertEquals($url, $service->getUrl());
        PHPUnit::assertEquals($content, $service->getContent());
        PHPUnit::assertEquals($expectedHeaders, $service->getHeaders());
    }

    private function mockResponse()
    {
        $mock = \Mockery::mock(Response::class);

        return $mock;
    }
}
