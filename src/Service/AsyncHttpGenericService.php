<?php
/**
 * Created by PhpStorm.
 * User: Lombardo
 * Date: 27/12/16
 * Time: 16:35
 */

namespace AsyncHttpClient\Service;

use React\HttpClient\Response;

class AsyncHttpGenericService implements AsyncHttpService
{

    /**
     * @var string
     */
    private $method;

    /**
     * @var string
     */
    private $url;
    
    /**
     * @var string
     */
    private $content;
    
    /**
     * @var callable
     */
    private $callback;

    
    public function __construct($method, $url, $content, callable $callback = null)
    {
        $this->method   = $method;
        $this->url      = $url;
        $this->content  = $content;
        $this->callback = $callback;
    }

    /**
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }
    
    /**
     * @return string
     */
    public function getContent()
    {
        if ($this->getMethod() == 'POST') {
            return $this->content;
        }
        
        return null;
    }
    
    /**
     * @return array
     */
    public function getHeaders()
    {
        if ($this->getMethod() == 'POST')
        {
            return [
                'Content-Length' => strlen($this->getContent()),
                'Content-Type' => 'application/x-www-form-urlencoded'
            ];
        }
        
        return null;
    }
    
    /**
     * @param          $data
     * @param Response $response
     *
     * @return void
     */
    public function execute($data, Response $response)
    {
        if (!is_null($this->callback)) {
            $callback = $this->callback;
            $callback($data, $response);
        }
    }
}
