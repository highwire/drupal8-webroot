<?php

namespace HighWire\Clients\Personalization;

use HighWire\Clients\Client;
use GuzzleHttp\Client as GuzzleClient;
use PersonalizationClient\Api\PreferencesApi;
use PersonalizationClient\Configuration;


/**
 * Preferences Client Class.
 */
class Preferences extends Client {

  use RemoveHeaderTrait;

  /**
   * Preferences swagger client
   *
   * @var \PersonalizationClient\Api\PreferencesApi
   */
  protected $preferencesSwaggerClient;

  /**
   * Create a new client object.
   *
   * @param \GuzzleHttp\Client $http_client
   *   A guzzle client object.
   * @param array $config
   *   Any configuration the client needs.
   */
  public function __construct(GuzzleClient $http_client, array $config = []) {
    parent::__construct($http_client, $config);
    $http_client->getConfig('handler')->push($this->removeJSONContentTypeHeader());
    $swagger_config = new Configuration();
    $swagger_config->setHost($http_client->getConfig('base_uri')->__toString());
    $this->preferencesSwaggerClient = new PreferencesApi($this->httpClient, $swagger_config);
  }

  /**
   * Implements php's _call method to make writing clients easier.
   *
   * @param string $name
   *   The name of the method being called.
   * @param array $arguments
   *   Any arguments passed to the method.
   *
   * @return mixed
   *   A response from the client or what ever the return
   *   is from the magic method that was invoked.
   */
  public function __call($name, array $arguments) {
    if (method_exists($this->preferencesSwaggerClient, $name)) {
      return call_user_func_array([$this->preferencesSwaggerClient, $name], $arguments);
    }
    elseif (!method_exists($this, $name)) {
      throw new \Exception("Method doesn't exist $name");
    }
  }

}
