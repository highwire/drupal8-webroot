<?php

use HighWire\Clients\Sass\Sass;
use HighWire\Clients\ClientFactory;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

class SassTest extends TestCase {

  public function testProxy() {
    $mock = new MockHandler([
        new Response(200, [], "test\n")
    ]);
    $handler = HandlerStack::create($mock);
    $client = ClientFactory::get('sass', ['guzzle-config' => ['handler'  => $handler]]);

    ob_start();
    $client->proxy('/corpus/1/2/3.atom', NULL, [], FALSE);
    $content = ob_get_contents();
    ob_end_clean();

    $this->assertEquals(trim($content), 'test');
  }

  public function testGet() {
    $mock = new MockHandler([
        new Response(200, [], "test")
    ]);
    $handler = HandlerStack::create($mock);
    $client = ClientFactory::get('sass', ['guzzle-config' => ['handler'  => $handler]]);

    $results = $client->getResource('/corpus/1/2/3.atom');

    $this->assertEquals(strval($results->getData()), 'test');
  }

  public function testHead() {
    $mock = new MockHandler([
        new Response(200, ['Foo-Header' => 'foo'])
    ]);
    $handler = HandlerStack::create($mock);
    $client = ClientFactory::get('sass', ['guzzle-config' => ['handler'  => $handler]]);

    $results = $client->headResource('/corpus/1/2/3.atom');

    $this->assertEquals($results->getData(), ['Foo-Header' => 'foo']);
  }

  public function testStatic() {
    $uri = 'sass://corpus/1/2/3.atom';
    $apath = '/corpus/1/2/3.atom';
    $this->assertEquals($apath, Sass::parseURI($uri));
    $this->assertEquals($uri, Sass::getURI($apath));
  }
}
