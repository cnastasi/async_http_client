<?php
/**
 * Created by PhpStorm.
 * User: Lombardo
 * Date: 27/12/16
 * Time: 14:59
 */

namespace AsyncHttpClient\Logger;


use PHPUnit_Framework_Assert as PHPUnit;

class AsyncHttpLoggerDefaultTest extends \PHPUnit_Framework_TestCase
{
    public function test()
    {
        $method = "GET";
        $url = "http://www.lombax.it/api.php";
        $data = "TEST_DATA";
        $startTime = microtime(true);

        $expectedLog = [
            'method'        => $method,
            'url'           => $url,
            'data'          => $data,
            'startTime'     => $startTime,
            'endTime'       => null,
        ];

        $logger = new AsyncHttpLoggerDefault();
        $logger->log($method, $url, $data, $startTime);
        $logger->logTotal($startTime);

        $result = $logger->getLogs();

        $expectedLog['endTime'] = $result[0]['endTime']; // TODO: i'm not really testing endTime now


        PHPUnit::assertEquals(2, count($result));
        PHPUnit::assertEquals($expectedLog, $result[0]);
        PHPUnit::assertArrayHasKey('total', $result[1]);

    }
}