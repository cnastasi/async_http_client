<?php
/**
 * Created by PhpStorm.
 * User: christian
 * Date: 12/24/16
 * Time: 8:40 PM
 */

namespace AsyncHttpClient;

use React\HttpClient\Response;

interface AsyncHttpService
{
    /**
     * @return string
     */
    public function getMethod();

    /**
     * @return string
     */
    public function getUrl();

    /**
     * @param          $data
     * @param Response $response
     *
     * @return mixed
     */
    public function execute($data, Response $response);


}