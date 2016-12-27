<?php
/**
 * Created by PhpStorm.
 * User: Lombardo
 * Date: 27/12/16
 * Time: 15:28
 */

namespace AsyncHttpClient\Logger;


use AsyncHttpClient\Helper\Time;

class AsyncHttpLoggerDefault implements AsyncHttpLogger
{
    /**
     * @var array
     */
    private $logs = [];

    /**
     * @var Time
     */
    private $time;

    /**
     * AsyncHttpLoggerDefault constructor.
     */
    public function __construct(Time $time)
    {
        $this->time = $time;
    }

    public function log($method, $url, $data, $startTime)
    {
        $endTime = $this->time->now();

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
        $endTime = $this->time->now();
        $this->logs[] = ['total' => $endTime - $startTime];
    }

    public function getLogs()
    {
        return $this->logs;
    }
}