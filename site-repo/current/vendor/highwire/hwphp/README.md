[![Build Status](https://travis-ci.com/highwire/hwphp.svg?token=iqGQWVg4tv3pbeo66uJ4&branch=master)](https://travis-ci.com/highwire/hwphp)
[![Build Status](https://scrutinizer-ci.com/g/highwire/hwphp/badges/build.png?b=master&s=e735af9c0d6e676fcd0804c23ab507515c1fe925)](https://scrutinizer-ci.com/g/highwire/hwphp/build-status/master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/highwire/hwphp/badges/quality-score.png?b=master&s=ffa4e95c087c1eb4139740755684d240fd46a08b)](https://scrutinizer-ci.com/g/highwire/hwphp/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/highwire/hwphp/badges/coverage.png?b=master&s=4514a1491e0d79ef0663c2a4d7cfef8a5b310465)](https://scrutinizer-ci.com/g/highwire/hwphp/?branch=master)

Getting Started
===============

#### Development environment
Make sure you have php 7.0.x installed on your machine.

```bash
git clone git@github.com:highwire/hwphp.git
cd hwphp
composer install
./vendor/bin/phpunit
```
If you see a message like:
```
Error:         No code coverage driver is available
```
Install xdebug on your machine to fix it.

### Running Drupal and Drupal Standards code sniffer
```
vendor/bin/phpcs --config-set installed_paths vendor/drupal/coder/coder_sniffer/
vendor/bin/phpcs --standard=Drupal --extensions=php,module,inc,install,test,profile,theme,css,info,txt,md src/
```
Clients
=======
The HWPHP Client api consists of extending the base HighWire\Clients\Client.php class. This abstract class is responsible for dealing with the guzzle client and provides some helper logic for writing async clients easier. In addition to the abstract Client class this library provides a ClientFactory class for instantiating clients based on settings stored in client.config.yml.

## Example of defining and using a new service
New service class:
File - src/HighWire/Clients/SomeNewClient/SomeNewClient.php
```php
<?php

namespace HighWire\Clients\SomeNewClient\SomeNewClient;

use HighWire\Clients\Client;

class SomeNewClient extends Client {

  function someRequestAsync($policy_name) {
    $request = $this->buildRequest('GET', "policy/$policy_name/definition");
    $http_promise = $this->sendAsync($request);
    $promise = new Promise(function () use (&$promise, $http_promise) {
        $resp = $http_promise->wait();
        $policy = new ExtractPolicy(strval($resp->getBody()));
        $hw_response = new HWResponse($resp, $policy);
        $promise->resolve($hw_response);
    });

    return $promise;
  }
}
```
#### Important note:
The client base class makes use of PHP magic methods for automatically creating a non async someRequest method. So for every request that your client makes, make sure to append 'Async' to the method. This method must return a GuzzleHttp\Promise\Promise. You should be able to copy someRequestAsync above and use it for each client you build.

Next add the service meta data to client.config.yml
File - File - src/HighWire/Clients/client.config.yml
```yml
a4d-extract:
  name: A4D Extract Service
  apiVersion: 1.0
  environmentBaseUrls:
    production: http://a4d-extract.highwire.org
    development: http://a4d-extract-dev.highwire.org
  description: Transform XML into JSON and using defined extract politices
  timeout: 5
  class: HighWire\Clients\A4DExtract\A4DExtract
access-control:  
  name: Access Control Service
  apiVersion: 1.0
  environmentBaseUrls:
    production: http://access-control.highwire.org
    development: http://access-control-dev.highwire.org
  description: Perform acesss authentication and authorizations
  timeout: 5
  class: HighWire\Clients\AccessControl\AccessControl
atom-lite:  
  name: AtomLite service
  apiVersion: 1.0
  environmentBaseUrls:
    production: http://atomlite-svc.highwire.org
    development: http://atomlite-svc-dev.highwire.org
  description: Get data from the atomlite service
  timeout: 5
  class: HighWire\Clients\AtomLite\AtomLite
some-new-service:  
    name: Some New Service
    apiVersion: 1.0
    environmentBaseUrls:
      production: http://atomlite-svc.highwire.org
      development: http://atomlite-svc-dev.highwire.org
    description: Some new service description
    timeout: 5
    class: HighWire\Clients\SomeNewClient\SomeNewClient  
```

Lastly to use this new service you would do the following:
```php
<?php
use HighWire\Clients\ClientFactory;

$client = ClientFactory::create('some-new-service');
$devClient = ClientFactory::create('some-new-service', [], 'development');

```


Libraries
=========

Elastic
-------

Elastic is a high-level library for querying and fetching items from ElasticSearch. Internally it uses the low-level [elasticsearch-php](https://github.com/elastic/elasticsearch-php) library.

#### Examples

```php
<?php

use HighWire\Elastic;

$elastic = new Elastic(["localhost:9200"]);


// Getting items by ID is very fast and uses the core elastic index

# Get item by ID
$item = $elastic->get('sass','/sci/353/6307/1482.atom');
print var_dump($item);

# Get multiple items by an array of IDs
$apaths = ['/sci/353/6307/1482.atom', '/bmj/352/8048.atom'];
$items = $elastic->GetMultiple('sass', $apaths);
foreach ($items as $apath => $item) {
  print var_dump($item);
}

// Query for items to fetch all items that match the key-value pairs provided.

# Fetch item by pisa-id
$item = $elastic->querySingle('sass', ['pisa' => 'sci;353/6307/1482']);
print var_dump($item);

# Fetch all bmj issues
$items = $this->elastic->query('sass', ['jcode' => 'bmj', 'atype-long' => 'journal-issue']);
foreach ($items as $apath => $item) {
  print var_dump($item);
}
```
