<?php

use HighWire\Clients\AtomCollections\AtomCollections;
use HighWire\Clients\ClientFactory;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

class AtomCollectionsClientTest extends TestCase {

  public function testGetMembership() {
    $mock = new MockHandler([
        new Response(200, [], file_get_contents(__DIR__  . '/../../Parser/atom-collections/membership.atom'))
    ]);
    $handler = HandlerStack::create($mock);
    $client = ClientFactory::get('atom-collections', ['guzzle-config' => ['handler'  => $handler]]);

    $membership = $client->getMembership('/sgrvv/31/6/1021.atom', 'springer');

    $this->assertNotEmpty($membership);
    $this->assertEquals(7, count($membership));
    $this->assertEquals(7, count($membership->getCategories()));
    $this->assertEquals('/sgrvv/31/6/1021.atom', $membership->apath());
  }

  public function testGetTermMembership() {
    $mock = new MockHandler([
        new Response(200, [], file_get_contents(__DIR__  . '/../../Parser/atom-collections/term_membership.xml'))
    ]);
    $handler = HandlerStack::create($mock);
    $client = ClientFactory::get('atom-collections', ['guzzle-config' => ['handler'  => $handler]]);

    $term_membership = $client->getTermMembership('f1c129ee', 'springer');

    $this->assertNotEmpty($term_membership);

    $apaths = [
      '/sgrworks/book/978-0-8261-4661-8/chapter/ch07.atom',
      '/sgrworks/book/978-0-8261-4661-8.atom',
      '/sgrworks/book/978-0-8261-4661-8/chapter/ch08.atom',
      '/sgrworks/book/978-0-8261-4661-8/chapter/ch01.atom',
      '/sgrworks/book/978-0-8261-4661-8/chapter/ch09.atom',
      '/sgrworks/book/978-0-8261-4661-8/chapter/ch02.atom',
      '/sgrworks/book/978-0-8261-4661-8/chapter/ch10.atom',
      '/sgrworks/book/978-0-8261-4661-8/chapter/ch03.atom',
      '/sgrworks/book/978-0-8261-4661-8/chapter/ch11.atom',
      '/sgrworks/book/978-0-8261-4661-8/chapter/ch04.atom',
      '/sgrworks/book/978-0-8261-4661-8/chapter/ch12.atom',
      '/sgrworks/book/978-0-8261-4661-8/chapter/ch13.atom',
      '/sgrworks/book/978-0-8261-4661-8/chapter/ch05.atom',
      '/sgrworks/book/978-0-8261-4661-8/chapter/ch14.atom',
      '/sgrworks/book/978-0-8261-4661-8/chapter/ch06.atom'
    ];

    $this->assertEquals($apaths, $term_membership->apaths());
  }

}
