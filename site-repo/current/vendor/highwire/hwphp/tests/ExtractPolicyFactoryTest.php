<?php

use HighWire\Clients\ClientFactory;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use HighWire\ExtractPolicyFactory;
use HighWire\Parser\ExtractPolicy\ExtractPolicy;
use PHPUnit\Framework\TestCase;

class ExtractPolicyFactoryTest extends TestCase {

  public function testGetPolicy() {
    $mock = new MockHandler([
        new Response(200, ['Content-Type' => 'application/json'], file_get_contents(__DIR__ . '/extract-policy-factory.test-policies.response.json')),
    ]);

    $handler = HandlerStack::create($mock);
    $atomlite = ClientFactory::get('atom-lite', ['guzzle-config' => ['handler' => $handler]]);
    $factory = new ExtractPolicyFactory($atomlite);
    $policy = $factory->get('drupal-journal');
    $this->assertEquals(get_class($policy), ExtractPolicy::class);
  }
}
