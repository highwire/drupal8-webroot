<?php

use HighWire\Clients\Taxonomy\Taxonomy;
use HighWire\Clients\ClientFactory;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

class TaxonomyClientTest extends TestCase {

  public function testGetTree() {
    $mock = new MockHandler([
        new Response(200, [], file_get_contents(__DIR__  . '/../../Parser/taxonomy/springer-subject.xml'))
    ]);
    $handler = HandlerStack::create($mock);
    $client = ClientFactory::get('taxonomy', ['guzzle-config' => ['handler'  => $handler]]);

    $tree = $client->getTree('springer', 'content', 'subject');
    
    $this->assertNotEmpty($tree);
    $this->assertEquals('springer', $tree->publisher());
    $this->assertEquals('content', $tree->collection());
    $this->assertEquals('subject', $tree->scheme());
  }

  public function testGetTreeList() {
    $mock = new MockHandler([
        new Response(200, [], file_get_contents(__DIR__  . '/../../Parser/taxonomy/springer-taxonomies.xml'))
    ]);
    $handler = HandlerStack::create($mock);
    $client = ClientFactory::get('taxonomy', ['guzzle-config' => ['handler'  => $handler]]);

    $tree_list = $client->getTreeList('springer');
    
    $this->assertNotEmpty($tree_list);
    $this->assertNotEmpty($tree_list->getTaxonomyTrees());
  }

}
