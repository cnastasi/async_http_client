<?php
/**
 * Created by PhpStorm.
 * User: christian
 * Date: 12/24/16
 * Time: 8:12 PM
 */

namespace AsyncHttpClient\Core;


use AsyncHttpClient\Logger\AsyncHttpLogger;
use React\EventLoop\LoopInterface;
use React\HttpClient\Client as ReactClient;
use React\HttpClient\Request;
use React\HttpClient\Response;

class AsyncHttpClientDefault implements AsyncHttpClient
{

    /**
     * @var AsyncHttpService[]
     */
    private $services = [];

    /**
     * @var ReactClient
     */
    private $client;

    /**
     * @var LoopInterface
     */
    private $loop;

    /**
     * @var AsyncHttpLogger
     */
    private $logger;

    /**
     * AsyncServiceExecutor constructor.
     *
     * @param ReactClient     $client
     * @param LoopInterface   $loop
     * @param AsyncHttpLogger $logger
     */
    public function __construct(ReactClient $client, LoopInterface $loop, AsyncHttpLogger $logger)
    {
        $this->client = $client;
        $this->loop   = $loop;
        $this->logger = $logger;
    }


    public function addService(AsyncHttpService $service)
    {
        $this->services[] = $service;
    }

    public function send()
    {
        $startTime = microtime(true);

        while (count($this->services) > 0) {
            $service = array_shift($this->services);
            $request = $this->createRequest($service);

            $this->defineHandlers($request, $service);

            $request->end();
        }

        $this->loop->run();

        $this->logger->logTotal($startTime);
    }

    private function createRequest(AsyncHttpService $service)
    {
        return $this->client->request($service->getMethod(), $service->getUrl());
    }

    private function defineHandlers(Request $request, AsyncHttpService $service)
    {
        $start = microtime(true);

        $request->on('response', function (Response $response) use ($start, $service) {

            $response->on('data', function ($data, Response $response) use ($start, $service) {

                $service->execute($data, $response);

                $this->logger->log($service->getMethod(), $service->getUrl(), $data, $start);
            }
            );

            // TODO: Implements error case
            // $response->on('error', ...);
        }
        );
    }
}