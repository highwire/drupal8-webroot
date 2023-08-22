<?php

use HighWire\Clients\ClientFactory;
use GuzzleHttp\Client as GuzzleClient;
use PHPUnit\Framework\TestCase;

class ClientFactoryTest extends TestCase {

  public function testServiceConfig() {
    $clients = ClientFactory::getClientConfig();
    $this->assertInternalType('array', $clients);
    $this->assertNotNull($clients);
    $this->assertArrayHasKey('access-control', $clients);
    foreach ($clients as $client_name => $client) {
      $this->assertArrayHasKey('name', $client);
      $this->assertArrayHasKey('apiVersion', $client);
      $this->assertArrayHasKey('environmentBaseUrls', $client);
      $this->assertArrayHasKey('description', $client);
      $this->assertArrayHasKey('timeout', $client);
    }

  }

  public function testFactoryConfig() {
    $atomx = ClientFactory::get('atomx');
    $this->assertEquals(get_class($atomx), 'HighWire\Clients\AtomX\AtomX');
  }

  public function testGetSingleClientConfig() {
    $atomlite = ClientFactory::getClientConfig('atom-lite');
    $this->assertInternalType('array', $atomlite);
    $this->assertEquals('Atom-lite service', $atomlite['name']);
  }

  /**
   * @expectedException Exception
   */
  public function testBadClient() {
    ClientFactory::getClientConfig('rabble-dabble-client');
  }

  /**
   * @expectedException Exception
   */
  public function testGetUndefinedClient() {
    ClientFactory::get('rabble-dabble-client');
  }

  public function testCustomURL() {
    $client_config = [];
    $client_config['env'] = 'custom';
    $client_config['custom_url'] = 'http://test.com';

    $atom_lite = ClientFactory::get('atom-lite', ['client-config' => $client_config]);
    $base_uri = $atom_lite->getGuzzleConfig('base_uri');
    $this->assertEquals('http://test.com', $base_uri->__toString());
  }

  /**
   * @expectedException Exception
   */
  public function testBadEnv() {
    $client_config = [];
    $client_config['env'] = 'gremlins';

    ClientFactory::get('atom-lite', ['client-config' => $client_config]);
  }

  /**
   * @expectedException Exception
   */
  public function testMissingCustomURL() {
    $client_config = [];
    $client_config['env'] = 'custom';

    ClientFactory::get('atom-lite', ['client-config' => $client_config]);
  }

  public function testBaseUriOverride() {
    $guzzle_config['base_uri'] = 'http://www.test.com';
    $atom_lite = ClientFactory::get('atom-lite', ['guzzle-config' => $guzzle_config]);
    $base_uri = $atom_lite->getGuzzleConfig('base_uri');
    $this->assertEquals('http://www.test.com', $base_uri->__toString());
  }

  public function testTimeout() {
    // Test override
    $guzzle_config['timeout'] = 100;
    $atom_lite = ClientFactory::get('atom-lite', ['guzzle-config' => $guzzle_config]);
    $this->assertEquals(100, $atom_lite->getGuzzleConfig('timeout'));
  }

  public function testClientConfig() {
    $client_config['env'] = 'development';
    $atom_lite = ClientFactory::get('atom-lite', ['client-config' => $client_config]);
    $base_uri = $atom_lite->getGuzzleConfig('base_uri');
    $clients = ClientFactory::getClientConfig();

    $this->assertEquals($clients['atom-lite']['environmentBaseUrls']['development'], $base_uri->__toString());

  }
}
