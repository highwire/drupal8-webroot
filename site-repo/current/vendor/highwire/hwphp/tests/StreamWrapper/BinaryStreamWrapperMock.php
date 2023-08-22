<?php

namespace HighWire\test\StreamWrapper;

use HighWire\StreamWrapper\BinaryStreamWrapper;
use HighWire\Clients\ClientFactory;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;

/**
 * HighWire Binary Stream Wrapper.
 */
class BinaryStreamWrapperMock extends BinaryStreamWrapper {

  /**
   * {@inheritdoc}
   */
  protected function client() {
    static $client;
    if (!empty($client)) {
      return $client;
    }
    $mock = new MockHandler([
      new Response(200, [], "test\n"),
      new Response(200, ['Content-Length' => 12345, 'Last-Modified' => 'Wed, 21 Oct 2015 07:28:00 UTC']),
      new Response(200, [], "testseek"),
    ]);
    $handler = HandlerStack::create($mock);
    $client = ClientFactory::get('binary', ['guzzle-config' => ['handler' => $handler]]);
    return $client;
  }

  /**
   * Helper method for testing protected client method.
   */
  public function getClient() {
    return $this->client();
  }

}
