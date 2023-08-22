<?php

use HighWire\Clients\ClientFactory;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use HighWire\Parser\ExtractPolicy\ExtractPolicy;
use HighWire\Exception\HighWireCorpusIdsNotFoundException;
use PHPUnit\Framework\TestCase;

class AtomLiteTest extends TestCase {
  public function testGetCorpusIds() {
    // Create a mock response guzzle handler
    $mock = new MockHandler([
        new Response(200, ['Content-Type' => 'application/json'], file_get_contents(__DIR__  . '/atomlite.corpus_ids.response.json')),
        new Response(200, ['Content-Type' => 'application/json'], file_get_contents(__DIR__  . '/atomlite.corpus_ids.response.json')),
    ]);

    $handler = HandlerStack::create($mock);

    $atomLite = ClientFactory::get('atom-lite', ['guzzle-config' => ['handler' => $handler]]);
    $ids = $atomLite->getCorpusIds('freebird2', 'item-mhe');
    $this->arrayHasKey('/freebird2/book/978-0-8261-2174-5/supersection/ch1057.atom', $ids);
    $this->assertEquals(count($ids), 5613);

    // Test async
    $promise = $atomLite->getCorpusIdsAsync('freebird2', 'item-mhe');
    $this->assertInstanceOf('\GuzzleHttp\Promise\Promise', $promise);
    $ids = $promise->wait();
    $this->arrayHasKey('/freebird2/book/978-0-8261-2174-5/supersection/ch1057.atom', $ids);
    $this->assertEquals(count($ids), 5613);
  }


  /**
   * @expectedException HighWire\Exception\HighWireCorpusIdsNotFoundException
   */
  public function testGetCorpusIdsNotfound() {
    // Create a mock response guzzle handler
    $mock = new MockHandler([
      new Response(200, ['Content-Type' => 'application/json'], ''),
    ]);

    $handler = HandlerStack::create($mock);

    $atomLite = ClientFactory::get('atom-lite', ['guzzle-config' => ['handler' => $handler]]);
    $ids = $atomLite->getCorpusIds('freebird2', 'item-mhe');
  }

  public function testGetId() {
    $mock = new MockHandler([
        new Response(200, ['Content-Type' => 'application/json'], file_get_contents(__DIR__ . '/atomlite.test-payload1.response.json')),
        new Response(200, ['Content-Type' => 'application/json'], file_get_contents(__DIR__ . '/atomlite.test-payload1.response.json')),
    ]);

    $handler = HandlerStack::create($mock);
    $atomlite = ClientFactory::get('atom-lite', ['guzzle-config' => ['handler' => $mock]]);
    $payload = $atomlite->get('/mheaeworks/book/9780070483156/front-matter/preface1.atom', 'item-mhe');
    $this->arrayHasKey('apath', $payload);
    $this->assertInternalType('array', $payload);
    $this->assertEquals($payload['apath'], '/mheaeworks/book/9780070483156/front-matter/preface1.atom');

    // Test async
    $promise = $atomlite->getAsync('/mheaeworks/book/9780070483156/front-matter/preface1.atom', 'item-mhe');
    $this->assertInstanceOf('\GuzzleHttp\Promise\Promise', $promise);
    $payload = $promise->wait();
    $this->arrayHasKey('apath', $payload);
    $this->assertInternalType('array', $payload);
    $this->assertEquals($payload['apath'], '/mheaeworks/book/9780070483156/front-matter/preface1.atom');

    $mock = new MockHandler([
        new Response(200, ['Content-Type' => 'application/json'], file_get_contents(__DIR__ . '/atomlite.test-payload1.response.json')),
        new Response(200, ['Content-Type' => 'application/json'], file_get_contents(__DIR__ . '/atomlite.test-payload1.response.json')),
    ]);

    $handler = HandlerStack::create($mock);
    // Test policy in config
    $client_config = ['policy-name' => 'item-mhe'];
    $atomlite = ClientFactory::get('atom-lite', ['guzzle-config' => ['handler' => $mock], 'client-config' => $client_config]);
    $payload = $atomlite->get('/mheaeworks/book/9780070483156/front-matter/preface1.atom');
    $this->arrayHasKey('apath', $payload);
    $this->assertInternalType('array', $payload);
    $this->assertEquals($payload['apath'], '/mheaeworks/book/9780070483156/front-matter/preface1.atom');

    $promise = $atomlite->getAsync('/mheaeworks/book/9780070483156/front-matter/preface1.atom');
    $this->assertInstanceOf('\GuzzleHttp\Promise\Promise', $promise);
    $payload = $promise->wait();
    $this->arrayHasKey('apath', $payload);
    $this->assertInternalType('array', $payload);
    $this->assertEquals($payload['apath'], '/mheaeworks/book/9780070483156/front-matter/preface1.atom');
  }

  /**
   * @expectedException Exception
   */
  public function testGetIdsAsyncMissingPolicy() {
    $mock = new MockHandler([
        new Response(200, ['Content-Type' => 'application/json'], file_get_contents(__DIR__ . '/atomlite.test-payload1.response.json')),
    ]);

    $handler = HandlerStack::create($mock);
    // Test policy in config
    $atomlite = ClientFactory::get('atom-lite', ['guzzle-config' => ['handler' => $mock]]);

    $promise = $atomlite->getAsync('/mheaeworks/book/9780070483156/front-matter/preface1.atom');
  }

  /**
   * @expectedException Exception
   */
  public function testMissingPolicyName() {
    $mock = new MockHandler([
        new Response(200, ['Content-Type' => 'application/json'], file_get_contents(__DIR__ . '/atomlite.test-payload1.response.json')),
    ]);

    $handler = HandlerStack::create($mock);
    // Test policy in config
    $atomlite = ClientFactory::get('atom-lite', ['guzzle-config' => ['handler' => $mock]]);
    // Exception is thrown here.
    $atomlite->get('/mheaeworks/book/9780070483156/front-matter/preface1.atom');

  }

  public function testGetMultiple() {
    $mock = new MockHandler([
        new Response(200, ['Content-Type' => 'application/json'], file_get_contents(__DIR__  . '/atomlite.test-multi-payload.response.json')),
        new Response(200, ['Content-Type' => 'application/json'], file_get_contents(__DIR__  . '/atomlite.test-multi-payload.response.json'))

    ]);

    $handler = HandlerStack::create($mock);
    $atomlite = ClientFactory::get('atom-lite', ['guzzle-config' => ['handler' => $handler]], 'development');

    $ids =  [
      0 => '/mheaeworks/book/9780070483156/front-matter/preface1.atom',
      1 => '/mheaeworks/book/9780070483156/front-matter/preface2.atom',
      2 => '/mheaeworks/book/9780070483156/front-matter/preface3.atom',
      3 => '/mheaeworks/book/9780070483156/front-matter/preface4.atom',
      4 => '/mheaeworks/book/9780070483156/front-matter/preface5.atom',
      5 => '/mheaeworks/book/9780070483156/front-matter/preface6.atom',
      6 => '/mheaeworks/book/9780070483156/front-matter/preface7.atom',
      7 => '/mheaeworks/book/9780070483156/front-matter/preface8.atom',
      8 => '/mheaeworks/book/9780070483156/front-matter/preface9.atom',
    ];

    $payloads = $atomlite->getMultiple($ids, 'item-mhe');
    $this->assertInternalType('array', $payloads);
    $i = 0;
    foreach ($payloads as $key => $payload) {
      $this->assertEquals($ids[$i], $payload['apath']);
      $this->assertInternalType('array', $payload);
      $this->arrayHasKey('apath', $payload);
      $this->assertEquals($payload['corpus'], 'mheaeworks');
      $i++;
    }
    // Make sure the payload has all ids
    foreach ($ids as $id) {
      $this->arrayHasKey($id, $payloads);
    }

    // Test async
    $promise = $atomlite->getMultipleAsync($ids, 'item-mhe');
    $this->assertInstanceOf('\GuzzleHttp\Promise\Promise', $promise);
    $payloads = $promise->wait();
    $this->assertInternalType('array', $payloads);
    $i = 0;
    foreach ($payloads as $key => $payload) {
      $this->assertEquals($ids[$i], $payload['apath']);
      $this->assertInternalType('array', $payload);
      $this->arrayHasKey('apath', $payload);
      $this->assertEquals($payload['corpus'], 'mheaeworks');
      $i++;
    }
    // Make sure the payload has all ids
    foreach ($ids as $id) {
      $this->arrayHasKey($id, $payloads);
    }
  }

  public function testGetMultipleClientUsingClientConfig() {
    $mock = new MockHandler([
      new Response(200, ['Content-Type' => 'application/json'], file_get_contents(__DIR__  . '/atomlite.test-multi-payload.response.json')),
    ]);

    $handler = HandlerStack::create($mock);
    $client_config = ['policy-name' => 'item-mhe'];
    $atomlite = ClientFactory::get('atom-lite', ['guzzle-config' => ['handler' => $handler], 'client-config' => $client_config], 'development');

    $ids =  [
      0 => '/mheaeworks/book/9780070483156/front-matter/preface1.atom',
      1 => '/mheaeworks/book/9780070483156/front-matter/preface2.atom',
      2 => '/mheaeworks/book/9780070483156/front-matter/preface3.atom',
      3 => '/mheaeworks/book/9780070483156/front-matter/preface4.atom',
      4 => '/mheaeworks/book/9780070483156/front-matter/preface5.atom',
      5 => '/mheaeworks/book/9780070483156/front-matter/preface6.atom',
      6 => '/mheaeworks/book/9780070483156/front-matter/preface7.atom',
      7 => '/mheaeworks/book/9780070483156/front-matter/preface8.atom',
      8 => '/mheaeworks/book/9780070483156/front-matter/preface9.atom',
    ];

    $payloads = $atomlite->getMultiple($ids);
    $this->assertInternalType('array', $payloads);
    $i = 0;
    foreach ($payloads as $key => $payload) {
      $this->assertEquals($ids[$i], $payload['apath']);
      $this->assertInternalType('array', $payload);
      $this->arrayHasKey('apath', $payload);
      $this->assertEquals($payload['corpus'], 'mheaeworks');
      $i++;
    }

    // Make sure the payload has all ids
    foreach ($ids as $id) {
      $this->arrayHasKey($id, $payloads);
    }
  }


  public function testGetMultipleAsyncClientUsingClientConfig() {
    $mock = new MockHandler([
      new Response(200, ['Content-Type' => 'application/json'], file_get_contents(__DIR__  . '/atomlite.test-multi-payload.response.json')),
      new Response(200, ['Content-Type' => 'application/json'], file_get_contents(__DIR__  . '/atomlite.test-multi-payload.response.json')),
    ]);

    $handler = HandlerStack::create($mock);
    $client_config = ['policy-name' => 'item-mhe'];
    $atomlite = ClientFactory::get('atom-lite', ['guzzle-config' => ['handler' => $handler], 'client-config' => $client_config], 'development');

    $ids =  [
      0 => '/mheaeworks/book/9780070483156/front-matter/preface1.atom',
      1 => '/mheaeworks/book/9780070483156/front-matter/preface2.atom',
      2 => '/mheaeworks/book/9780070483156/front-matter/preface3.atom',
      3 => '/mheaeworks/book/9780070483156/front-matter/preface4.atom',
      4 => '/mheaeworks/book/9780070483156/front-matter/preface5.atom',
      5 => '/mheaeworks/book/9780070483156/front-matter/preface6.atom',
      6 => '/mheaeworks/book/9780070483156/front-matter/preface7.atom',
      7 => '/mheaeworks/book/9780070483156/front-matter/preface8.atom',
      8 => '/mheaeworks/book/9780070483156/front-matter/preface9.atom',
    ];

    $payloads = $atomlite->getMultipleAsync($ids);
    $promise = $atomlite->getMultipleAsync($ids, 'item-mhe');
    $this->assertInstanceOf('\GuzzleHttp\Promise\Promise', $promise);
    $payloads = $promise->wait();
    $this->assertInternalType('array', $payloads);
    $i = 0;
    foreach ($payloads as $key => $payload) {
      $this->assertEquals($ids[$i], $payload['apath']);
      $this->assertInternalType('array', $payload);
      $this->arrayHasKey('apath', $payload);
      $this->assertEquals($payload['corpus'], 'mheaeworks');
      $i++;
    }
    // Make sure the payload has all ids
    foreach ($ids as $id) {
      $this->arrayHasKey($id, $payloads);
    }
  }

  /**
   * @expectedException Exception
   */
  public function testGetMultipleAsyncMissingPolicy() {
    $mock = new MockHandler([
      new Response(200, ['Content-Type' => 'application/json'], file_get_contents(__DIR__  . '/atomlite.test-payload1.response.json')),
    ]);

    $handler = HandlerStack::create($mock);
    $atomlite = ClientFactory::get('atom-lite', ['guzzle-config' => ['handler' => $handler]], 'development');
    $ids = [
      '/mheaeworks/book/9780070483156/front-matter/preface1.atom',
      '/freebird2/book/978-0-8261-2174-5/supersection/ch165.atom',
      '/freebird2/book/978-0-8261-2174-5/supersection/ch165/section/ch165lev1sec1.atom',
    ];
    $payloads = $atomlite->getMultipleAsync($ids);
  }

  /**
   * @expectedException Exception
   */
  public function testGetMultipleMissingPolicy() {
    $mock = new MockHandler([
      new Response(200, ['Content-Type' => 'application/json'], file_get_contents(__DIR__  . '/atomlite.test-payload1.response.json')),
    ]);

    $handler = HandlerStack::create($mock);
    $atomlite = ClientFactory::get('atom-lite', ['guzzle-config' => ['handler' => $handler]], 'development');
    $ids = [
      '/mheaeworks/book/9780070483156/front-matter/preface1.atom',
      '/freebird2/book/978-0-8261-2174-5/supersection/ch165.atom',
      '/freebird2/book/978-0-8261-2174-5/supersection/ch165/section/ch165lev1sec1.atom',
    ];
    $payloads = $atomlite->getMultiple($ids);
  }

  public function testGetRawExtractPolicy() {
    $mock = new MockHandler([
        new Response(200, ['Content-Type' => 'application/json'], file_get_contents(__DIR__  . '/atomlite.test-policies.response.json')),
    ]);

    $handler = HandlerStack::create($mock);
    $atomlite = ClientFactory::get('atom-lite', ['guzzle-config' => ['handler' => $handler]]);
    $policy = $atomlite->getRawExtractPolicy('item-bits');

    $this->assertNotEmpty($policy);
    $extract = new ExtractPolicy($policy);

    $this->assertEquals('item-bits', $extract->getName());
  }

  /**
   * @expectedException HighWire\Exception\HighWirePolicyNotFoundException
   */
  public function testMissingGetRawExtractPolicy() {
    $mock = new MockHandler([
        new Response(200, ['Content-Type' => 'application/json'], ''),
    ]);

    $handler = HandlerStack::create($mock);
    $atomlite = ClientFactory::get('atom-lite', ['guzzle-config' => ['handler' => $handler]]);
    $policy = $atomlite->getRawExtractPolicy('item-mhe');
    $this->assertNotEmpty($policy);
    $extract = new ExtractPolicy($policy);
    $this->assertEquals('item-mhe', $extract->getName());
  }

  public function testGetPayloadAsync() {
    $mock = new MockHandler([
      new Response(200, ['Content-Type' => 'application/json'], file_get_contents(__DIR__  . '/atomlite.test-payload-single.response.json')),
    ]);

    $handler = HandlerStack::create($mock);
    $client_config = ['policy-name' => 'item-mhe'];
    $atomlite = ClientFactory::get('atom-lite', ['guzzle-config' => ['handler' => $handler], 'client-config' => $client_config], 'development');
    $response = $atomlite->getPayload('/freebird2/book/978-0-8261-2174-5/supersection/ch164/section/ch164lev1sec1.atom', 'some-mime-type');
    $payload = json_decode($response->getData(), TRUE);

    $this->arrayHasKey('apath', $payload);
    $this->assertInternalType('array', $payload);
    $this->assertEquals($payload['apath'], '/freebird2/book/978-0-8261-2174-5/supersection/ch164/section/ch164lev1sec1.atom');
  }

  public function testGetPolicies() {
    $mock = new MockHandler([
        new Response(200, ['Content-Type' => 'application/json'], file_get_contents(__DIR__ . '/atomlite-test.policies.json')),
    ]);

    $handler = HandlerStack::create($mock);
    $atomlite = ClientFactory::get('atom-lite', ['guzzle-config' => ['handler' => $handler]]);
    $result = $atomlite->getPolicesAsync();
    $policies = $result->wait();
    $this->assertInternalType('array', $policies);
    $this->assertEquals(1, count($policies));
    $ecom = $policies[0];
    $this->assertEquals('ecommerce', $ecom['policyId']);
    $this->assertEquals('ecommerce', $ecom['name']);
    $this->assertEquals('ecommerce', $ecom['name']);

  }

}
