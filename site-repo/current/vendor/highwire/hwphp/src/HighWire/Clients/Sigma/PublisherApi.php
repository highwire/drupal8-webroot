<?php

namespace HighWire\Clients\Sigma;

use GuzzleHttp\Client as GuzzleClient;
use HighWire\Clients\Client;
use GuzzleHttp\Promise\Promise;
use HighWire\Clients\HWResponse;


/**
 * Class Publisher Api
 *
 * @package HighWire\Clients\Sigma
 */
class PublisherApi extends Client {

  /**
   * The context for the Sigma publisher api.
   *
   * @var string
   */
  protected $context;

  /**
   * The api key for the Sigma publisher api.
   *
   * @var string
   */
  protected $apiKey;

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
    $this->apiKey = $config['api_key'];
    $this->context = $config['context'];
  }

  /**
   * Get trusted referrer urls for a publisher profile id.
   *
   * @param string $profile_id
   *   The publsiher profile Id.
   *
   * @return \GuzzleHttp\Promise\Promise
   *   Returns a guzzle promise object that can be full filed by calling wait()
   */
  public function getTrustedReferrerUrlsForProfileAsync(string $profile_id): Promise {
    $params = [];

    $headers = [];
    $headers['Authorization'] = "APIKEY " . $this->apiKey;

    $request = $this->buildRequest('GET', '/api/organizations/' . $profile_id . '/trusted-referrers', NULL, $headers);
    $http_promise = $this->sendAsync($request, $params);
    $promise = new Promise(function () use (&$promise, $http_promise) {
      $request = $http_promise->wait();
      $response = $request->getBody();
      $raw_response = $response->getContents();
      $trusted_referrers = json_decode($raw_response);

      $hw_response = new HWResponse($request, $trusted_referrers);
      $promise->resolve($hw_response);

    });

    return $promise;
  }

  /**
   * Check if the publisher api key is set.
   *
   * @return bool
   *   True if the api key is set, false if it is not.
   */
  public function hasApiKey() {
    return !empty($this->apiKey) ?? FALSE;
  }

  /**
   * Check if the trusted referrer is set in sigma.
   *
   * @param string $profile_id
   *   The profile id.
   * @param string $base_url
   *   The subscriber base url set in the casa token client.
   * @param array $trusted_referrers
   *   The array of trusted referrers from getTrustedReferrerUrlsForProfileAsync.
   * 
   * @return bool
   *   Trusted referrer sigma status boolean.
   */
  public function casaIdentityExists(string $profile_id, string $base_url, array $trusted_referrers) {
    $casa_identity = $base_url . $profile_id;
    $exists = FALSE;
    foreach ($trusted_referrers as $trusted_referrer) {
      if ($casa_identity = $trusted_referrer->trustedReferrerUrl) {
        $exists = TRUE;
        break;
      }
    }

    return $exists;
  }

}
