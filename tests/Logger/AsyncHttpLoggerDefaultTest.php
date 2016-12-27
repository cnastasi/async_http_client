<?php
/**
 * Created by PhpStorm.
 * User: Lombardo
 * Date: 27/12/16
 * Time: 14:59
 */

namespace AsyncHttpClient\Logger;


use AsyncHttpClient\Helper\Time;
use PHPUnit_Framework_Assert as PHPUnit;

class AsyncHttpLoggerDefaultTest extends \PHPUnit_Framework_TestCase
{
    public function test()
    {
        $method = "GET";
        $url = "http://www.lombax.it/api.php";
        $data = "TEST_DATA";
        $startTime = microtime(true);
        $deltaTime = 2; // the amount of time between start and end
        $endTime = $startTime + $deltaTime;

        $expectedLog = [
            'method'        => $method,
            'url'           => $url,
            'data'          => $data,
            'startTime'     => $startTime,
            'endTime'       => $endTime,
        ];

        $time = $this->mockTime($endTime);

        $logger = new AsyncHttpLoggerDefault($time);
        $logger->log($method, $url, $data, $startTime);
        $logger->logTotal($startTime);

        $result = $logger->getLogs();

        PHPUnit::assertEquals(2, count($result));
        PHPUnit::assertEquals($expectedLog, $result[0]);
        PHPUnit::assertArrayHasKey('total', $result[1]);
        PHPUnit::assertEquals($deltaTime, $result[1]['total']);

        \Mockery::close();

    }

    private function mockTime($now)
    {
        $mock = \Mockery::mock(Time::class);
        $mock->shouldReceive('now')->twice()->andReturn($now);
        return $mock;
    }
}