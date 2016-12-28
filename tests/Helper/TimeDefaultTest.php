<?php
/**
 * Created by PhpStorm.
 * User: christian
 * Date: 12/28/16
 * Time: 12:44 AM
 */

namespace Helper;


use AsyncHttpClient\Helper\TimeDefault;

class TimeDefaultTest extends \PHPUnit_Framework_TestCase
{
    public function test()
    {
        $time = new TimeDefault();

        $now = $time->now();

        \PHPUnit_Framework_Assert::assertEquals('double', gettype($now));
        \PHPUnit_Framework_Assert::assertEquals(microtime(true), $now, 'Now is not now', 0.1);
    }
}