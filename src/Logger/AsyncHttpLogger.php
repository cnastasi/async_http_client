<?php
/**
 * Created by PhpStorm.
 * User: christian
 * Date: 12/26/16
 * Time: 12:57 PM
 */

namespace AsyncHttpClient\Logger;


interface AsyncHttpLogger
{
    public function log($method, $url, $data, $startTime);

    public function logTotal($startTime);
}