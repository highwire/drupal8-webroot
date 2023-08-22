<?php

use HighWire\Clients\ClientFactory;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use HighWire\Clients\HWResponse;
use GuzzleHttp\Promise\Promise;
use HighWire\Parser\Markup\Markup;
use PHPUnit\Framework\TestCase;

class MarkupClientTest extends TestCase {

  /**
   * Test getMarkupMultiple.
   */
  public function testGetMarkupMultipleAsync() {
    // Create a mock response guzzle handler.
    // @note this should be updated once the
    // markup service starts return json responses.
    $mock = new MockHandler([
      new Response(200, ['Content-Type' => 'application/xhtml+xml;charset=utf-8'], file_get_contents(__DIR__  . '/markup.single.response.json')),
    ]);

    $handler = HandlerStack::create($mock);
    $markup = ClientFactory::get('markup', ['guzzle-config' => ['handler' => $handler]]);
    $apaths = ['/freebird2/book/978-1-6170-5269-9/part/part03/chapter/242-248.atom'];
    $promise = $markup->getMarkupMultipleAsync($apaths, 'fulltext');
    $this->assertInstanceOf(Promise::class, $promise);
    $response = $promise->wait();
    $this->assertInstanceOf(HWResponse::class, $response);
    $this->assertNotEmpty($response->getData());
    $this->assertArrayHasKey($apaths[0], $response->getData());
    $markups = $response->getData();
    $this->assertNotEmpty($markups[$apaths[0]]);
  }

  public function testGetMMarkupSingleAsycn() {
    // Create a mock response guzzle handler.
    // @note this should be updated once the
    // markup service starts return json responses.
    $mock = new MockHandler([
      new Response(200, ['Content-Type' => 'application/xhtml+xml;charset=utf-8'], file_get_contents(__DIR__  . '/markup.single.response.json')),
    ]);

    $handler = HandlerStack::create($mock);
    $markup = ClientFactory::get('markup', ['guzzle-config' => ['handler' => $handler]]);
    $apath = '/freebird2/book/978-1-6170-5269-9/part/part03/chapter/242-248.atom';
    $promise = $markup->getMarkupSingleAsync($apath, 'fulltext');
    $this->assertInstanceOf(Promise::class, $promise);
    $response = $promise->wait();
    $this->assertInstanceOf(HWResponse::class, $response);
    $this->assertNotEmpty($response->getData());
    $markup = $response->getData();
    $this->assertNotEmpty($markup);
  }

  public function testGetProfiles() {
    $mock = new MockHandler([
      new Response(200, ['Content-Type' => 'application/xhtml+xml;charset=utf-8'], file_get_contents(__DIR__ . '/markup-profiles.json')),
      new Response(200, ['Content-Type' => 'application/xhtml+xml;charset=utf-8'], file_get_contents(__DIR__ . '/markup-profiles.json')),
    ]);

    $handler = HandlerStack::create($mock);
    $markup = ClientFactory::get('markup', ['guzzle-config' => ['handler' => $handler]]);
    $response = $markup->getProfiles();
    $this->assertEquals(get_class($response), HWResponse::class);
    $profiles = $response->getData();
    $this->assertEquals(6, count($profiles));

    $promise = $markup->getProfilesAsync();

    $this->assertEquals(get_class($promise), Promise::class);
    $response = $promise->wait();
    $this->assertEquals(get_class($response), HWResponse::class);
    $profiles = $response->getData();
    $this->assertEquals(6, count($profiles));
  }

  public function testMultipleProfilesAndApaths() {
    $mock = new MockHandler([
      new Response(200, ['Content-Type' => 'application/vnd.collection+json;charset=utf-8'], file_get_contents(__DIR__  . '/markup-profiles-and-apaths.json')),
    ]);

    $handler = HandlerStack::create($mock);
    $markup = ClientFactory::get('markup', ['guzzle-config' => ['handler' => $handler]]);
    $apaths = ['/sgrworks/book/978-0-8261-3778-4/chapter/ch16.atom', '/sgrworks/book/978-0-8261-3778-4.atom'];
    $profiles = ['abstract', 'metatags'];
    $response = $markup->getMarkupMultiple($apaths, $profiles);
    $this->assertEquals(get_class($response), HWResponse::class);
    $data = $response->getData();
    $this->assertNotEmpty($data);
    $this->assertNotEmpty($data['abstract']);
    $this->assertNotEmpty($data['metatags']);
    $this->assertNotEmpty($data['abstract']['/sgrworks/book/978-0-8261-3778-4/chapter/ch16.atom']);
    $this->assertNotEmpty($data['abstract']['/sgrworks/book/978-0-8261-3778-4.atom']);
    $this->assertNotEmpty($data['metatags']['/sgrworks/book/978-0-8261-3778-4/chapter/ch16.atom']);
    $this->assertNotEmpty($data['metatags']['/sgrworks/book/978-0-8261-3778-4.atom']);
    $this->assertEquals(get_class($data['metatags']['/sgrworks/book/978-0-8261-3778-4.atom']), Markup::class);
    $this->assertEquals(get_class($data['abstract']['/sgrworks/book/978-0-8261-3778-4.atom']), Markup::class);
    $this->assertEquals(get_class($data['metatags']['/sgrworks/book/978-0-8261-3778-4/chapter/ch16.atom']), Markup::class);
    $this->assertEquals(get_class($data['abstract']['/sgrworks/book/978-0-8261-3778-4/chapter/ch16.atom']), Markup::class);
  }
}
