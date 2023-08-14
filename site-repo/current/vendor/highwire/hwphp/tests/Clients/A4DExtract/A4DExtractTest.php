<?php

use HighWire\Clients\ClientFactory;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

class A4DExtractTest extends TestCase {

  public function testExtract() {
    $mock = new MockHandler([
        new Response(200, [], file_get_contents(__DIR__  . '/bmj.352.8048.extract.json'))
    ]);
    $handler = HandlerStack::create($mock);
    $client = ClientFactory::get('a4d-extract', ['guzzle-config' => ['handler'  => $handler]]);
    $result = $client->extract('drupal-40', '/bmj/352/8048.atom');
    $this->assertEquals($result->getData()['apath'], '/bmj/352/8048.atom');
  }

  public function testFetchPolicy() {
    $mock = new MockHandler([
        new Response(200, [], file_get_contents(__DIR__  . '/../../assets/extract.definition.test.xml'))
    ]);
    $handler = HandlerStack::create($mock);
    $client = ClientFactory::get('a4d-extract', ['guzzle-config' => ['handler'  => $handler]]);
    $result = $client->getPolicy('drupal-40');
    $fields = $result->getData()->fields();
    $this->assertEquals($fields['apath']->name(), 'apath');
  }
}
