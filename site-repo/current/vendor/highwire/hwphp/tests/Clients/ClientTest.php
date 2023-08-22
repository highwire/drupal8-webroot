<?php

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use HighWire\Clients\ClientFactory;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use HighWire\Clients\A4DExtract\A4DExtract;
use PHPUnit\Framework\TestCase;

class ClientTest extends TestCase {
  public function testClient() {
    $hw_clients = ClientFactory::getClientConfig();

    $mock = new MockHandler([
        new Response(200, ['Content-Type' => 'text/plain'], '{"test": "Hello World"}'),
        new Response(200, ['Content-Type' => 'text/plain'], '{"test": "Hello World"}'),
    ]);

    $handler = HandlerStack::create($mock);

    $gz_client = new Client([
      'base_uri' => $hw_clients['a4d-extract']['environmentBaseUrls']['production'],
      'handler' => $handler
    ]);

    $a4d = new A4DExtract($gz_client);
    $hw_response = $a4d->extract('doesnt-matter', 'doesnt-matter');
    $this->assertInstanceOf('HighWire\Clients\HWResponse', $hw_response);
    $this->assertArrayHasKey('test', $hw_response->getData());
    $this->assertEquals($hw_clients['a4d-extract']['environmentBaseUrls']['production'], $a4d->getGuzzleConfig('base_uri'));

    // Test async
    $promise = $a4d->extractAsync('doesnt-matter', 'doesnt-matter');
    $this->assertInstanceOf('GuzzleHttp\Promise\Promise', $promise);
    $hw_response = $promise->wait();
    $this->assertInstanceOf('HighWire\Clients\HWResponse', $hw_response);
  }
}
