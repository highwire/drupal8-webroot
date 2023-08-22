<?php

use HighWire\Clients\Binary\Binary;
use HighWire\Clients\ClientFactory;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

class BinaryTest extends TestCase {

  public function testMetadata() {
    $mock = new MockHandler([
        new Response(200, [], '{"corpus":"freebird","ingestKey":"3321ec26b4e582c0","binaryHash":"f2ca1bb6c7e907d06dafe4687e579fce76b37e4e93b7605022da52e6ccc26fd2","filename":"phayes.txt","mediaType":"text/plain","bytes":5,"lastModified":"2017-07-14T17:57:41Z"}')
    ]);
    $handler = HandlerStack::create($mock);
    $client = ClientFactory::get('binary', ['guzzle-config' => ['handler'  => $handler]]);

    $result = $client->getMetadata('3321ec26b4e582c0/f2ca1bb6c7e907d06dafe4687e579fce76b37e4e93b7605022da52e6ccc26fd2');
    $metadata = $result->getData();

    $this->assertEquals($metadata['corpus'], 'freebird');
    $this->assertEquals($metadata['ingestKey'], '3321ec26b4e582c0');
    $this->assertEquals($metadata['binaryHash'], 'f2ca1bb6c7e907d06dafe4687e579fce76b37e4e93b7605022da52e6ccc26fd2');
    $this->assertEquals($metadata['filename'], 'phayes.txt');
    $this->assertEquals($metadata['mediaType'], 'text/plain');
    $this->assertEquals($metadata['bytes'], 5);
    $this->assertEquals($metadata['lastModified'], '2017-07-14T17:57:41Z');
  }

  public function testMultipleMetadata() {
    $mock = new MockHandler([
        new Response(200, [], '[{"corpus":"freebird","ingestKey":"3321ec26b4e582c0","binaryHash":"f2ca1bb6c7e907d06dafe4687e579fce76b37e4e93b7605022da52e6ccc26fd2","filename":"phayes.txt","mediaType":"text/plain","bytes":5,"lastModified":"2017-07-14T17:57:41Z"}]')
    ]);
    $handler = HandlerStack::create($mock);
    $client = ClientFactory::get('binary', ['guzzle-config' => ['handler'  => $handler]]);

    $result = $client->getMultipleMetadata(['3321ec26b4e582c0/f2ca1bb6c7e907d06dafe4687e579fce76b37e4e93b7605022da52e6ccc26fd2']);
    $metadata = $result->getData();
    $item_metadata = $metadata['3321ec26b4e582c0/f2ca1bb6c7e907d06dafe4687e579fce76b37e4e93b7605022da52e6ccc26fd2'];

    $this->assertEquals($item_metadata['corpus'], 'freebird');
    $this->assertEquals($item_metadata['ingestKey'], '3321ec26b4e582c0');
    $this->assertEquals($item_metadata['binaryHash'], 'f2ca1bb6c7e907d06dafe4687e579fce76b37e4e93b7605022da52e6ccc26fd2');
    $this->assertEquals($item_metadata['filename'], 'phayes.txt');
    $this->assertEquals($item_metadata['mediaType'], 'text/plain');
    $this->assertEquals($item_metadata['bytes'], 5);
    $this->assertEquals($item_metadata['lastModified'], '2017-07-14T17:57:41Z');
  }

  public function testProxy() {
    $mock = new MockHandler([
        new Response(200, [], "test\n")
    ]);
    $handler = HandlerStack::create($mock);
    $client = ClientFactory::get('binary', ['guzzle-config' => ['handler'  => $handler]]);

    ob_start();
    $client->proxy('3321ec26b4e582c0/f2ca1bb6c7e907d06dafe4687e579fce76b37e4e93b7605022da52e6ccc26fd2', NULL, [], FALSE);
    $content = ob_get_contents();
    ob_end_clean();

    $this->assertEquals(trim($content), 'test');
  }

  public function testGet() {
    $mock = new MockHandler([
        new Response(200, [], "test")
    ]);
    $handler = HandlerStack::create($mock);
    $client = ClientFactory::get('binary', ['guzzle-config' => ['handler'  => $handler]]);

    $results = $client->getBinary('3321ec26b4e582c0/f2ca1bb6c7e907d06dafe4687e579fce76b37e4e93b7605022da52e6ccc26fd2');

    $this->assertEquals(strval($results->getData()), 'test');
  }

  public function testHead() {
    $mock = new MockHandler([
        new Response(200, ['Foo-Header' => 'foo'])
    ]);
    $handler = HandlerStack::create($mock);
    $client = ClientFactory::get('binary', ['guzzle-config' => ['handler'  => $handler]]);

    $results = $client->headBinary('3321ec26b4e582c0/f2ca1bb6c7e907d06dafe4687e579fce76b37e4e93b7605022da52e6ccc26fd2');

    $this->assertEquals($results->getData(), ['Foo-Header' => 'foo']);
  }

  public function testStatic() {
    // Binary ID Parsing
    $binary_id = '3321ec26b4e582c0/f2ca1bb6c7e907d06dafe4687e579fce76b37e4e93b7605022da52e6ccc26fd2';
    $binary_id_parsed = ['ingest_key' => '3321ec26b4e582c0', 'binary_hash' => 'f2ca1bb6c7e907d06dafe4687e579fce76b37e4e93b7605022da52e6ccc26fd2'];
    $this->assertEquals(Binary::parseId($binary_id), $binary_id_parsed);
    $this->assertEquals(Binary::Id($binary_id_parsed['ingest_key'], $binary_id_parsed['binary_hash']), $binary_id);

    // Binary ID Parsing with filename
    $this->assertEquals(Binary::parseId($binary_id . '/filename.png'), array_merge($binary_id_parsed, ['filename' => 'filename.png']));
    
    // Binary URI Parsing
    $uri = 'binary://corpus/3321ec26b4e582c0/f2ca1bb6c7e907d06dafe4687e579fce76b37e4e93b7605022da52e6ccc26fd2/filename.png';
    $uri_parsed = ['corpus' => 'corpus', 'ingest_key' => '3321ec26b4e582c0', 'binary_hash' => 'f2ca1bb6c7e907d06dafe4687e579fce76b37e4e93b7605022da52e6ccc26fd2', 'filename' => 'filename.png'];
    $this->assertEquals($uri_parsed, Binary::parseURI($uri));
    $this->assertEquals(Binary::getURI($uri_parsed['corpus'], $uri_parsed['ingest_key'], $uri_parsed['binary_hash'], $uri_parsed['filename']), $uri);
    $this->assertEquals(Binary::binaryIdFromURI($uri), $binary_id);
  }
}
