<?php

use HighWire\Clients\ClientFactory;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

/**
 * Class DateTest
 */
class DateClientTest extends TestCase {

  public function testGetDates() {
    $mock = new MockHandler([
      new Response(200, [], file_get_contents(__DIR__  . '/datesvc-response.json'))
    ]);

    $handler = HandlerStack::create($mock);

    $datesvc = ClientFactory::get('date', ['guzzle-config' => ['handler'  => $handler]]);

    $resp = $datesvc->getDates(['/sgrargg/38/1/109.atom']);
    $dates = $resp->getData();
    $this->assertEquals($dates["r-released"]["/sgrargg/38/1/109.atom"]['date'], '2017-01-01T00:00:00Z');
  }
}
