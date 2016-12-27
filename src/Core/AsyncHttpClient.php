<?php

namespace AsyncHttpClient\Core;

use AsyncHttpClient\Service\AsyncHttpService;

/**
 * Created by PhpStorm.
 * User: christian
 * Date: 12/25/16
 * Time: 7:23 PM
 */

interface AsyncHttpClient
{
    public function addService(AsyncHttpService $service);

    public function send();
}