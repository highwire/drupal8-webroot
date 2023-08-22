<?php

use HighWire\Clients\ClientFactory;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

class UsageStatsTest extends TestCase {

  public function testSingleArticle() {

    // Test with combined
    $mock = new MockHandler([
      new Response(200, [], '<results:results corpus-code="jcb" master-id="103/3/1021" resource-id="103/3/1021" doi="10.1083/jcb.103.3.1021" xmlns:results="http://schema.highwire.org/SQL/results">
      <results:result type="query">
        <results:result-set>
          <results:row>
            <results:master_resource_id>103/3/1021</results:master_resource_id>
            <results:platform>highwire</results:platform>
            <results:use_month>201107</results:use_month>
            <results:abstract>5</results:abstract>
            <results:full>0</results:full>
            <results:pdf>8</results:pdf>
          </results:row>
          <results:row>
            <results:master_resource_id>103/3/1021</results:master_resource_id>
            <results:use_month>201106</results:use_month>
            <results:abstract>3</results:abstract>
            <results:full>0</results:full>
            <results:pdf>2</results:pdf>
          </results:row>
        </results:result-set>
      </results:result>
      </results:results>
      ')
    ]);
    $handler = HandlerStack::create($mock);
    $client = ClientFactory::get('usage-stats', ['guzzle-config' => ['handler'  => $handler]]);

    $result = $client->singleArticle('/jcb/103/3/1021.atom');
    $usage_stats = $result->getData();

    $this->assertEquals(2, count($usage_stats));
    $this->assertEquals("2011", $usage_stats[0]['year']);
    $this->assertEquals("07", $usage_stats[0]['month']);
    $this->assertEquals(5, $usage_stats[0]['abstract']);
    $this->assertEquals(0, $usage_stats[0]['full']);
    $this->assertEquals(8, $usage_stats[0]['pdf']);
    $this->assertEquals("highwire", $usage_stats[0]['platform']);
    $this->assertEquals("highwire-pmc", $usage_stats[1]['platform']);

    // Test without combined
    $mock = new MockHandler([
      new Response(200, [], '<results:results corpus-code="jcb" master-id="103/3/1021" resource-id="103/3/1021" doi="10.1083/jcb.103.3.1021" xmlns:results="http://schema.highwire.org/SQL/results">
      <results:result type="query">
        <results:result-set>
          <results:row>
            <results:master_resource_id>71/15/1167</results:master_resource_id>
            <results:platform>highwire</results:platform>
            <results:use_month>201709</results:use_month>
            <results:abstract>23</results:abstract>
            <results:full>0</results:full>
            <results:pdf>0</results:pdf>
          </results:row>
          <results:row>
            <results:platform>pmc</results:platform>
            <results:use_month>201709</results:use_month>
            <results:abstract>7</results:abstract>
            <results:full>212</results:full>
            <results:pdf>225</results:pdf>
          </results:row>
        </results:result-set>
      </results:result>
      </results:results>
      ')
    ]);
    $handler = HandlerStack::create($mock);
    $client = ClientFactory::get('usage-stats', ['guzzle-config' => ['handler'  => $handler]]);

    $result = $client->singleArticle('neurology/71/15/1167.atom', FALSE);
    $usage_stats = $result->getData();

    $this->assertEquals(2, count($usage_stats));
    $this->assertEquals("2017", $usage_stats[0]['year']);
    $this->assertEquals("09", $usage_stats[0]['month']);
    $this->assertEquals(23, $usage_stats[0]['abstract']);
    $this->assertEquals(0, $usage_stats[0]['full']);
    $this->assertEquals(0, $usage_stats[0]['pdf']);
    $this->assertEquals("highwire", $usage_stats[0]['platform']);
    $this->assertEquals("2017", $usage_stats[1]['year']);
    $this->assertEquals("09", $usage_stats[1]['month']);
    $this->assertEquals(7, $usage_stats[1]['abstract']);
    $this->assertEquals(212, $usage_stats[1]['full']);
    $this->assertEquals(225, $usage_stats[1]['pdf']);
    $this->assertEquals("pmc", $usage_stats[1]['platform']);
  }

  public function testSingleArticleYear() {
    $mock = new MockHandler([
      new Response(200, [], '<results:results xmlns:results="http://schema.highwire.org/SQL/results">
      <results:result type="query">
        <results:result-set>
          <results:row>
            <results:master_resource_id>103/3/1021</results:master_resource_id>
            <results:use_date>20181027</results:use_date>
            <results:abstract>4</results:abstract>
            <results:full_text>1</results:full_text>
            <results:pdf>1</results:pdf>
            <results:powerpoint>0</results:powerpoint>
          </results:row>
          <results:row>
            <results:master_resource_id>103/3/1021</results:master_resource_id>
            <results:use_date>20181026</results:use_date>
            <results:abstract>1</results:abstract>
            <results:full_text>0</results:full_text>
            <results:pdf>0</results:pdf>
            <results:powerpoint>0</results:powerpoint>
          </results:row>
        </results:result-set>
      </results:result>
      </results:results>
      ')
    ]);
    $handler = HandlerStack::create($mock);
    $client = ClientFactory::get('usage-stats', ['guzzle-config' => ['handler'  => $handler]]);

    $result = $client->singleArticleYear('/jcb/103/3/1021.atom');
    $usage_stats = $result->getData();

    $this->assertEquals(2, count($usage_stats));
    $this->assertEquals("2018", $usage_stats[0]['year']);
    $this->assertEquals("10", $usage_stats[0]['month']);
    $this->assertEquals("27", $usage_stats[0]['day']);
    $this->assertEquals(4, $usage_stats[0]['abstract']);
    $this->assertEquals(1, $usage_stats[0]['full']);
    $this->assertEquals(1, $usage_stats[0]['pdf']);
    $this->assertEquals(0, $usage_stats[0]['powerpoint']);
    $this->assertEquals("highwire-pmc", $usage_stats[0]['platform']);
  }

}
