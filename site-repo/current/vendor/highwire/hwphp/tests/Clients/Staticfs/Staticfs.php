<?php

use HighWire\Clients\ClientFactory;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

class StaticfsTest extends TestCase {

  public function testMostRead() {

    $mock = new MockHandler([
      new Response(200, [], '<feed xmlns="http://www.w3.org/2005/Atom" xmlns:nlm="http://schema.highwire.org/NLM/Journal" xmlns:r="http://schema.highwire.org/Revision" xmlns:hwp="http://schema.highwire.org/Journal" xmlns:c="http://schema.highwire.org/Compound" xmlns:app="http://www.w3.org/2007/app" xmlns:x="http://www.w3.org/1999/xhtml" xmlns:l="http://schema.highwire.org/Linking" xmlns:xlink="http://www.w3.org/1999/xlink">
      <entry nlm:article-type="research-article" xmlns:app="http://www.w3.org/2007/app" xmlns:c="http://schema.highwire.org/Compound" xmlns:l="http://schema.highwire.org/Linking" xmlns:r="http://schema.highwire.org/Revision" xmlns:hwp="http://schema.highwire.org/Journal" xmlns:nlm="http://schema.highwire.org/NLM/Journal" xmlns:x="http://www.w3.org/1999/xhtml" xmlns:atom="http://www.w3.org/2005/Atom" xmlns:hpp="http://schema.highwire.org/Publishing"><link rel="self" href="/sgrcl/9/3/130.atom"/></entry>
      <entry nlm:article-type="research-article" xmlns:app="http://www.w3.org/2007/app" xmlns:c="http://schema.highwire.org/Compound" xmlns:l="http://schema.highwire.org/Linking" xmlns:r="http://schema.highwire.org/Revision" xmlns:hwp="http://schema.highwire.org/Journal" xmlns:nlm="http://schema.highwire.org/NLM/Journal" xmlns:x="http://www.w3.org/1999/xhtml" xmlns:atom="http://www.w3.org/2005/Atom" xmlns:hpp="http://schema.highwire.org/Publishing"><link rel="self" href="/sgrcl/9/3/153.atom"/></entry>
      <entry nlm:article-type="research-article" xmlns:app="http://www.w3.org/2007/app" xmlns:c="http://schema.highwire.org/Compound" xmlns:l="http://schema.highwire.org/Linking" xmlns:r="http://schema.highwire.org/Revision" xmlns:hwp="http://schema.highwire.org/Journal" xmlns:nlm="http://schema.highwire.org/NLM/Journal" xmlns:x="http://www.w3.org/1999/xhtml" xmlns:atom="http://www.w3.org/2005/Atom" xmlns:hpp="http://schema.highwire.org/Publishing"><link rel="self" href="/sgrcl/9/3/125.atom"/></entry>
      </feed>
      ')
    ]);
    $handler = HandlerStack::create($mock);
    $client = ClientFactory::get('staticfs', ['guzzle-config' => ['handler'  => $handler]]);

    $result = $client->mostRead("sgrcl");;
    $most_read = $result->getData();

    $expected = [
      '/sgrcl/9/3/130.atom',
      '/sgrcl/9/3/153.atom',
      '/sgrcl/9/3/125.atom'
    ];

    $this->assertEquals($most_read, $expected);
  }

  public function testMostCited() {

    $mock = new MockHandler([
      new Response(200, [], '<feed xmlns="http://www.w3.org/2005/Atom" xmlns:nlm="http://schema.highwire.org/NLM/Journal" xmlns:r="http://schema.highwire.org/Revision" xmlns:hwp="http://schema.highwire.org/Journal" xmlns:c="http://schema.highwire.org/Compound" xmlns:app="http://www.w3.org/2007/app" xmlns:x="http://www.w3.org/1999/xhtml" xmlns:l="http://schema.highwire.org/Linking" xmlns:xlink="http://www.w3.org/1999/xlink">
      <entry nlm:article-type="research-article" xmlns:app="http://www.w3.org/2007/app" xmlns:c="http://schema.highwire.org/Compound" xmlns:l="http://schema.highwire.org/Linking" xmlns:r="http://schema.highwire.org/Revision" xmlns:hwp="http://schema.highwire.org/Journal" xmlns:nlm="http://schema.highwire.org/NLM/Journal" xmlns:x="http://www.w3.org/1999/xhtml" xmlns:atom="http://www.w3.org/2005/Atom" xmlns:hpp="http://schema.highwire.org/Publishing"><link rel="self" href="/sgrcl/1/1/27.atom"/></entry>
      <entry nlm:article-type="research-article" xmlns:app="http://www.w3.org/2007/app" xmlns:c="http://schema.highwire.org/Compound" xmlns:l="http://schema.highwire.org/Linking" xmlns:r="http://schema.highwire.org/Revision" xmlns:hwp="http://schema.highwire.org/Journal" xmlns:nlm="http://schema.highwire.org/NLM/Journal" xmlns:x="http://www.w3.org/1999/xhtml" xmlns:atom="http://www.w3.org/2005/Atom" xmlns:hpp="http://schema.highwire.org/Publishing"><link rel="self" href="/sgrcl/2/2/22.atom"/></entry>
      </feed>
      ')
    ]);
    $handler = HandlerStack::create($mock);
    $client = ClientFactory::get('staticfs', ['guzzle-config' => ['handler'  => $handler]]);

    $result = $client->mostCited("sgrcl");
    $most_cited = $result->getData();

    $expected =  [
      '/sgrcl/1/1/27.atom',
      '/sgrcl/2/2/22.atom'
    ];

    $this->assertEquals($most_cited, $expected);
  }

}
