<?php
/**
 * Created by PhpStorm.
 * User: christian
 * Date: 12/24/16
 * Time: 8:40 PM
 */

namespace AsyncHttpClient\Service;

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
     * @return string
     */
    public function getContent();
    
    /**
     * @return array
     */
    public function getHeaders();

    /**
     * @param          $data
     * @param Response $response
     *
     * @return mixed
     */
    public function execute($data, Response $response);


}
