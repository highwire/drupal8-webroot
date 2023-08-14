<?php

use HighWire\Clients\ClientFactory;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

class AlertsTest extends TestCase {

  public function testhandleGetAllUsingGET() {
    $mock = new MockHandler([
        new Response(200, [], file_get_contents(__DIR__  . '/user.alerts.json'))
    ]);
    $handler = HandlerStack::create($mock);
    $client = ClientFactory::get('alerts', ['guzzle-config' => ['handler'  => $handler]]);
    $alerts = $client->handleGetAvailableUsingGET('sgrworks', NULL, 'pwaterz@gmail.com');
    $this->assertInternalType('array', $alerts);
    $this->assertInstanceOf(PersonalizationClient\Model\AlertResource::class, $alerts[0]);
  }


  public function testhandleGetAllUsingGETAsync() {
    $mock = new MockHandler([
        new Response(200, [], file_get_contents(__DIR__  . '/user.alerts.json'))
    ]);
    $handler = HandlerStack::create($mock);
    $client = ClientFactory::get('alerts', ['guzzle-config' => ['handler'  => $handler]]);
    $promise = $client->handleGetAvailableUsingGETAsync('sgrworks', NULL, 'pwaterz@gmail.com');
    $alerts = $promise->wait();
    $this->assertInternalType('array', $alerts);
    $this->assertInstanceOf(PersonalizationClient\Model\AlertResource::class, $alerts[0]);
    }

}
