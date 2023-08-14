<?php

use HighWire\Clients\ClientFactory;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

class EmbargoTest extends TestCase {

  public function testGetEmbargoState() {
    $mock = new MockHandler([
        new Response(200, [], file_get_contents(__DIR__  . '/apaths_state.json'))
    ]);
    $handler = HandlerStack::create($mock);
    $client = ClientFactory::get('embargo', ['guzzle-config' => ['handler'  => $handler]]);
    $apaths = [
      '/sgrworks/book/978-0-8261-0964-4/part/part02/chapter/ch12.atom',
      '/sgrworks/book/978-0-8261-0964-4/part/part02/chapter/ch13.atom',
      '/sgrworks/book/978-0-8261-0964-4/part/part02/chapter/ch14.atom',
      '/sgrworks/book/978-0-8261-0964-4/part/part03.atom',
    ];
    $resp = $client->getEmbargoState($apaths);
    $data = $resp->getData();
    foreach ($data as $result) {
      $this->assertTrue(in_array($result['apath'], $apaths));
      $this->assertTrue(array_key_exists('corpus', $result));
      $this->assertTrue(array_key_exists('state', $result));
      $this->assertTrue(array_key_exists('updated', $result));
    }
  }

  /**
   * @expectedException \Exception
   */
  public function testGetEmbargoStateBadApaths() {
    $mock = new MockHandler([
        new Response(200, [], file_get_contents(__DIR__  . '/bad_apaths.json'))
    ]);
    $handler = HandlerStack::create($mock);
    $client = ClientFactory::get('embargo', ['guzzle-config' => ['handler'  => $handler]]);
    $apaths = [
      '/sgrworks/book/978-0-8261-0964-4/part/part02/chapter/ch12.atoms',
    ];

    $resp = $client->getEmbargoState($apaths);
  }

}
