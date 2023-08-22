<?php

use HighWire\Clients\AtomCollections\AtomCollections;
use HighWire\Clients\ClientFactory;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

class AtomLiteReprocessorTest extends TestCase {

  public function testGetMembership() {
    $mock = new MockHandler([
        new Response(200, [], 'Resource reprocessed successfully')
    ]);
    $handler = HandlerStack::create($mock);
    $client = ClientFactory::get('atom-lite-reprocessor', ['guzzle-config' => ['handler'  => $handler]]);

    $promise = $client->indexApathAsync('/sgrworks/book/978-0-8261-2988-8/chapter/ch09.atom');
    $this->assertInstanceOf('\GuzzleHttp\Promise\Promise', $promise);
    $response = $promise->wait();
    $this->assertInstanceOf('\HighWire\Clients\HWResponseInterface', $response);
  }

}
