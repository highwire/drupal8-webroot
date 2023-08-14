<?php

use HighWire\Clients\Atom\Atom;
use HighWire\Clients\ClientFactory;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

class AtomTest extends TestCase {

  public function testGet() {
    $mock = new MockHandler([
        new Response(200, [], "test"),
        new Response(200, [], "test")
    ]);
    $handler = HandlerStack::create($mock);
    $client = ClientFactory::get('sass', ['guzzle-config' => ['handler'  => $handler]]);

    $results = $client->getResource('/corpus/1/2/3.atom');
    $results = $client->getResource('/corpus/1/2/3.atom', ['with-ancestors' => TRUE]);

    $this->assertEquals(strval($results->getData()), 'test');
  }

  public function testGetFrorests() {
    $mock = new MockHandler([
        new Response(200, [], file_get_contents(__DIR__  . '/get_forest_response.xml'))
    ]);
    $handler = HandlerStack::create($mock);
    $client = ClientFactory::get('atom', ['guzzle-config' => ['handler'  => $handler]]);

    $results = $client->getAtomForests();
    $data = $results->getData();
    $this->assertEquals(count($data), 1);
    $this->assertEquals($data[0], 'marklogic-dev-01');
  }

  public function testPathsFromPattern() {
    $mock = new MockHandler([
        new Response(200, [], file_get_contents(__DIR__  . '/get_forest_response.xml')),
        new Response(200, [], file_get_contents(__DIR__  . '/paths_from_pattern.xml'))
    ]);

    $handler = HandlerStack::create($mock);
    $client = ClientFactory::get('atom', ['guzzle-config' => ['handler'  => $handler]]);

    $paths = $client->pathsFromPattern('/*.atom');
    $this->assertEquals(count($paths), 7);
    $this->assertEquals($paths[0], '/mheaeworks/book/9780070045316/back-matter/appendix1.atom');
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

}
