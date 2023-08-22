<?php

namespace HighWire\Clients\CasaToken;

use HighWire\Clients\Client;
use GuzzleHttp\Client as GuzzleClient;
use CasaTokenClient\Api\TokensApi;
use CasaTokenClient\Configuration;


/**
 * Casa Token Client Class.
 */
class Token extends Client {

  /**
   * Casa Token swagger client.
   *
   * @var \CasaTokenClient\Api\TokensApi
   */
  protected $tokenSwaggerClient;

  /**
   * Client context.
   *
   * @var string
   */
  protected $context;

  /**
   * Client subscriber base url.
   *
   * @var string
   */
  protected $subscriberBaseUrl;

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
    $swagger_config = new Configuration();
    $swagger_config->setHost($http_client->getConfig('base_uri')->__toString());
    if (isset($config['context'])) {
      $this->context = $config['context'];
    }

    if (isset($config['subscriber_base_url'])) {
      $this->subscriberBaseUrl = $config['subscriber_base_url'];
    }

    $this->tokenSwaggerClient = new TokensApi($this->httpClient, $swagger_config);
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
    if (method_exists($this->tokenSwaggerClient, $name)) {
      return call_user_func_array([$this->tokenSwaggerClient, $name], $arguments);
    }
    elseif (!method_exists($this, $name)) {
      throw new \Exception("Method doesn't exist $name");
    }
  }

  /**
   * Get the client.
   *
   * @return \CasaTokenClient\Api\TokensApi
   *   A swagger casa token api client.
   */
  public function getClient(array $config = []) {
    return $this->tokenSwaggerClient;
  }

  /**
   * Get the client context.
   *
   * @return string
   *   The client context setting.
   */
  public function getContext() {
    return $this->context;
  }

  /**
   * Get the subscriber base url.
   *
   * @return string
   *   The client context setting.
   */
  public function getSubscriberBaseUrl() {
    return $this->subscriberBaseUrl;
  }

}
