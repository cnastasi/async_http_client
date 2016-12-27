<?php
/**
 * Created by PhpStorm.
 * User: Lombardo
 * Date: 27/12/16
 * Time: 15:28
 */

namespace AsyncHttpClient\Logger;


class AsyncHttpLoggerDefault implements AsyncHttpLogger
{
    private $logs = [];

    /**
     * AsyncHttpLoggerDefault constructor.
     */
    public function __construct()
    {

    }

    public function log($method, $url, $data, $startTime)
    {
        $endTime = microtime(true);

        // TODO: Implement log() method.
        $logLine = [
            'method'        => $method,
            'url'           => $url,
            'data'          => $data,
            'startTime'     => $startTime,
            'endTime'       => $endTime,
        ];

        $this->logs[] = $logLine;
    }

    public function logTotal($startTime)
    {
        $endTime = microtime(true);
        $this->logs[] = ['total' => $endTime - $startTime];
    }

    public function getLogs()
    {
        return $this->logs;
    }
}