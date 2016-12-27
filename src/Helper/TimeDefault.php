<?php
/**
 * Created by PhpStorm.
 * User: Lombardo
 * Date: 27/12/16
 * Time: 16:30
 */

namespace AsyncHttpClient\Helper;

class TimeDefault implements Time
{
    public function now()
    {
        return microtime(true);
    }
}