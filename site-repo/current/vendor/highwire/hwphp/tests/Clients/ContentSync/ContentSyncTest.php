<?php

use HighWire\Clients\ClientFactory;
use HighWire\Clients\HWResponse;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

/**
 * Class catalogTest
 */
class ContentSyncTest extends TestCase {

  public function testGetCounter() {
    $mock = new MockHandler([
      new Response(200, [], file_get_contents(__DIR__ . '/counter_response.txt'))
    ]);

    $handler = HandlerStack::create($mock);

    $content_sync = ClientFactory::get('content-sync', ['guzzle-config' => ['handler'  => $handler]]);
    $resp = $content_sync->getCounter();
    $counter = $resp->getData();
    $this->assertEquals($counter, '5285053');
  }

}
