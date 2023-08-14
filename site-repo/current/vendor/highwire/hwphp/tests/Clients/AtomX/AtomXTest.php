<?php

use HighWire\Clients\AtomX\AtomX;
use GuzzleHttp\Ring\Client\MockHandler;
use Elasticsearch\ClientBuilder;
use HighWire\Parser\ExtractPolicy\ExtractPolicy;
use Monolog\Logger;
use PHPUnit\Framework\TestCase;

class AtomXTest extends TestCase {

  protected function getStreamingPayloadMockClient($file) {
    $atomx = new AtomX(['somehost']);
    $handler = new MockHandler(['status' => 200, 'transfer_stats' => ['total_time' => 100], 'body' => fopen(__DIR__ . '/' . $file, 'r')]);
    $builder = ClientBuilder::create();
    $builder->setHosts(['somehost']);
    $builder->setSelector('\Elasticsearch\ConnectionPool\Selectors\StickyRoundRobinSelector');
    $builder->setHandler($handler);
    $atomx->setClient($builder->build());
    return $atomx;
  }

  public function testGet() {
    $atomx = $this->getStreamingPayloadMockClient('atomx.test-get.response.json');
    $item = $atomx->get('/bjres.atom');
    $this->assertEquals('/bjres.atom', $item['apath']);
  }

  /**
   * @expectedException HighWire\Exception\HighWirePayloadNotFoundException
   */
  public function testGetMissingPayload() {
    $atomx = new AtomX(['somehost']);
    $string = '';
    $stream = fopen('php://memory', 'r+');
    fwrite($stream, $string);
    rewind($stream);
    $handler = new MockHandler(['status' => 200, 'transfer_stats' => ['total_time' => 100], 'body' => $stream]);
    $builder = ClientBuilder::create();
    $builder->setHosts(['somehost']);
    $builder->setSelector('\Elasticsearch\ConnectionPool\Selectors\StickyRoundRobinSelector');
    $builder->setHandler($handler);
    $atomx->setClient($builder->build());
    $atomx->get('/missing/1.atom');
  }

  /**
   * @expectedException HighWire\Exception\HighWireFreebirdSchemaNotFound
   */
  public function testGetMissingFreebirdSchema() {
    $atomx = new AtomX(['somehost']);
    $string = '';
    $stream = fopen('php://memory', 'r+');
    fwrite($stream, $string);
    rewind($stream);
    $handler = new MockHandler(['status' => 404, 'transfer_stats' => ['total_time' => 100], 'body' => $stream]);
    $builder = ClientBuilder::create();
    $builder->setHosts(['somehost']);
    $builder->setSelector('\Elasticsearch\ConnectionPool\Selectors\StickyRoundRobinSelector');
    $builder->setHandler($handler);
    $atomx->setClient($builder->build());
    $extract_policy = new ExtractPolicy(file_get_contents(__DIR__ . '/atomx.test-get-freebird-schema-extract-policy.xml'));
    $atomx->getFreebirdSchema($extract_policy, ['some-missing-corpus']);
  }

  public function testGetIndexes() {
    $atomx = new AtomX(['somehost']);
    $extract_policy = new ExtractPolicy(file_get_contents(__DIR__ . '/atomx.test-get-freebird-schema-extract-policy.xml'));
    $atomx->setPolicy($extract_policy);
    $atomx->setCorpora(['freebird']);
    $indexes = $atomx->getIndexes();
    $this->assertInternalType('array', $indexes);
    $this->assertEquals('freebird-journal:freebird', $indexes[0]);
  }


  /**
   * @expectedException HighWire\Exception\HighWirePayloadNotFoundException
   */
  public function testGetMultipleMissingPayload() {
    $atomx = new AtomX(['somehost']);
    $string = '';
    $stream = fopen('php://memory','r+');
    fwrite($stream, $string);
    rewind($stream);
    $handler = new MockHandler(['status' => 200, 'transfer_stats' => ['total_time' => 100], 'body' => $stream]);
    $builder = ClientBuilder::create();
    $builder->setHosts(['somehost']);
    $builder->setSelector('\Elasticsearch\ConnectionPool\Selectors\StickyRoundRobinSelector');
    $builder->setHandler($handler);
    $atomx->setClient($builder->build());
    $atomx->getMultiple(['/missing/1.atom', '/missing/2.atom']);
  }

  public function testGetMultiple() {
    $atomx = $this->getStreamingPayloadMockClient('atomx.test-get-multiple.response.json');
    $items = $atomx->getMultiple(['/bjres/1/1/1/F4.atom', '/bjres/1/1/1/F1/graphic-1.atom']);
    $this->assertEquals(2, count($items));
    $this->assertArrayHasKey('/bjres/1/1/1/F4.atom', $items);
    $this->assertArrayHasKey('/bjres/1/1/1/F1/graphic-1.atom', $items);
    $this->assertEquals('/bjres/1/1/1/F4.atom', reset($items)['apath']);
  }

  public function testQuerySingle() {
    $atomx = $this->getStreamingPayloadMockClient('atomx.test-query-single.response.json');
    $atomx->setIndexes(['freebird-journal:bjres']);
    $item = $atomx->querySingle(['pisa' => 'bjres;1/1']);
    $this->assertEquals('/bjres/1/1.atom', $item['apath']);
    $item = $atomx->querySingle(['pisa' => 'bjres;1/1']);
    $this->assertEmpty($item);
  }

  public function testQuery() {
    $atomx = $this->getStreamingPayloadMockClient('atomx.test-query.response.json');
    $atomx->setIndexes(['freebird-journal:bjres']);
    $items = $atomx->query(['jcode' => 'bjres', 'atype-long' => 'journal-issue']);
    $this->assertEquals(62, $atomx->numResults());
  }

  public function testGetFreebirdSchema() {
    $atomx = $this->getStreamingPayloadMockClient('atomx.test-get-freebird-schema.response.json');
    // This extract policy is deliberately missing a field that is present in atomx reqponse
    // The field missing is <field name="atom-id">atom:id[1]/text()</field>
    $extract_policy = new ExtractPolicy(file_get_contents(__DIR__ . '/atomx.test-get-freebird-schema-extract-policy.xml'));
    $schema = $atomx->getFreebirdSchema($extract_policy, array('bmj'));
    $this->assertEquals($schema->getId(), 'freebird-journal');
    $all_types = $schema->getItemTypes();
    $this->assertEquals(count($all_types), 7);
    $this->assertArrayHasKey('journal-article', $all_types);
    $this->assertNotEmpty($schema->getItemSanitizedByName('journal_article'));
    $this->assertNotEmpty($schema->getAllFields());
    $this->assertEquals(count($schema->getAllFields()), 70);
    $fields = $schema->getAllFields();
    $this->assertEquals('some label', $fields['pisa']->getLabel());
    $this->assertEquals('some description', $fields['pisa']->getDescription());
    $journal_article = $schema->getItemByName('journal-article');
    $this->assertNotEmpty($journal_article);
    $article_categories_field = $journal_article->getFieldByName('article-categories');
    $this->assertNotEmpty($article_categories_field);
    $this->assertEquals($article_categories_field->getType(), 'structure');
    $this->assertNotEmpty($article_categories_field->isMultiple());
    $child_fields = $article_categories_field->getChildFields();
    $this->assertNotEmpty($child_fields);
    $article_categories_field_name_field = $article_categories_field->getChildFieldByName('name');
    $this->assertNotEmpty($article_categories_field_name_field);
    $this->assertEquals($article_categories_field_name_field->getType(), 'text');
    $this->assertEquals($article_categories_field_name_field->getName(), 'name');
  }

  public function testGetCorpusIds() {
    // Note that we don't acutally have a good way of testing this since the mock client can't handle multiple requests
    $atomx = $this->getStreamingPayloadMockClient('atomx.test-get-corpus-ids.response.1.json');
    $ids = $atomx->getCorpusIds('freebird2', 'item-bits');
    $this->assertEquals(5000, count($ids));
    $this->assertContains('/freebird2/book/978-0-8261-0684-1/part/part2/chapter/ch15.atom', $ids);
  }

  public function testGetCorpusIdsMissingPolicy() {
    $this->expectException('\Exception');

    // Note that we don't acutally have a good way of testing this since the mock client can't handle multiple requests
    $atomx = $this->getStreamingPayloadMockClient('atomx.test-get-corpus-ids.response.1.json');
    $ids = $atomx->getCorpusIds('freebird2');

  }

  public function testSearchMissingIndexes() {
    $this->expectException('Exception');

    $atomx = new AtomX(['some-host']);
    $atomx->query(['some search']);

  }

  public function testAddingLogger() {
    $config = [];
    $config['logger'] = new Logger('somelogger');

    $atomx = new AtomX(['somehost'], $config);
    $this->addToAssertionCount(1);
  }
}
