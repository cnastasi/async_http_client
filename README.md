[![SensioLabsInsight](https://insight.sensiolabs.com/projects/48fc9351-e25a-4ff9-8559-f1030031a707/big.png)](https://insight.sensiolabs.com/projects/48fc9351-e25a-4ff9-8559-f1030031a707)
[![Code Climate](https://codeclimate.com/github/cnastasi/async_http_client/badges/gpa.svg)](https://codeclimate.com/github/cnastasi/async_http_client)
[![Build Status](https://travis-ci.org/cnastasi/async_http_client.svg?branch=master)](https://travis-ci.org/cnastasi/async_http_client)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/cnastasi/async_http_client/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/cnastasi/async_http_client/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/cnastasi/async_http_client/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/cnastasi/async_http_client/?branch=master)

# Async Http Client
This simple client permits to execute multiple request in parallel, in a non blocking way.

## Prerequisites
- composer
- php >=5.6

## Installation
`composer require cnastasi\async_http_client`

## Usage
```php

use AsyncHttpClient\Core\AsyncHttpClient;
use AsyncHttpClient\Core\AsyncHttpClientDefault;
use AsyncHttpClient\Helper\TimeDefault;
use AsyncHttpClient\Logger\AsyncHttpLoggerDefault;
use AsyncHttpClient\Service\AsyncHttpGenericService;


$loop               = \React\EventLoop\Factory::create();
$dnsResolverFactory = new \React\Dns\Resolver\Factory();
$dnsResolver        = $dnsResolverFactory->createCached('8.8.8.8', $loop);
$factory            = new \React\HttpClient\Factory();
$client             = $factory->create($loop, $dnsResolver);
$logger             = new AsyncHttpLoggerDefault(new TimeDefault());

$asyncClient = new AsyncHttpClientDefault($client, $loop, $logger);

$service = new AsyncHttpGenericService('GET', 'http://www.google.it', null, function ($data, $request) {
    // Do something
});

$anotherService = new AsyncHttpGenericService('POST', 'http://www.another.service.com', http_build_query(['postfield1' => 'value']) , function ($data, $request) {
    // Do something more
});

$asyncClient->addService($service);
$asyncClient->addService($anotherService);

$asyncClient->send(); // code execution will block here and the HTTP calls will be dispatched in parallel

// the code execution will continue only after all http calls are dispatched and returned (callback called)
// do other stuff here

```
## Contributing
1. Fork it!
2. Create your feature branch: `git checkout -b my-new-feature`
3. Commit your changes: `git commit -am 'Add some feature'`
4. Push to the branch: `git push origin my-new-feature`
5. Submit a pull request :D

## History
That's the way how I survived the last Christmas :P 

## Credits
Thanks to [Fabio Lombardo](https://github.com/lombax85 "Fabio Lombardo github's page")

## License
[MIT License](https://github.com/cnastasi/async_http_client/blob/master/LICENSE)
