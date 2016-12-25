<?php
/**
 * Created by PhpStorm.
 * User: christian
 * Date: 12/24/16
 * Time: 8:12 PM
 */

namespace AsyncHttpClient;


use React\EventLoop\LoopInterface;
use React\HttpClient\Client as ReactClient;
use React\HttpClient\Response;

class AsyncHttpClientDefault implements AsyncHttpClient
{
    const METHOD   = 0;
    const URL      = 1;
    const CALLBACK = 2;

    /**
     * @var ReactClient
     */
    private $client;

    /**
     * @var LoopInterface
     */
    private $loop;

    private $stats;

    /**
     * AsyncServiceExecutor constructor.
     *
     * @param ReactClient $client
     */
    public function __construct(ReactClient $client, LoopInterface $loop)
    {
        $this->client = $client;
        $this->loop   = $loop;
    }

    /**
     * @var callable[]
     */
    private $services = [];

    public function addService(AsyncHttpService $service)
    {
        $this->services[] = $service;
    }

    public function send()
    {
        $start = microtime(true);

        $stats = [];

        while (count($this->services) > 0) {
            /** @var AsyncHttpService $service */
            $service = array_shift($this->services);

            $request = $this->client->request($service->getMethod(), $service->getUrl());

            $request->on('response', function (Response $response) use ($service, $start, &$stats) {

                $response->on('data', function ($data, Response $response) use ($service, $start, &$stats) {
                    $service->execute($data, $response);

                    $stats[] = [
                        'response' => $data,
                        'elapsed'  => sprintf('%.3f s', microtime(true) - $start)
                    ];
                });

                // TODO: Implements error case
                // $response->on('error', ...);
            });
            $request->end();
        }

        $this->loop->run();

        $stats [] = ['total' => sprintf('%.3f s', microtime(true) - $start)];

        $this->stats = $stats;

    }

    public function getStats()
    {
        return $this->stats;
    }
}