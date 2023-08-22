<?php

namespace HighWire\Clients\AIRecommendations;

use GuzzleHttp\Client as GuzzleClient;
use HighWire\Clients\Client;
use GuzzleHttp\Promise\Promise;
use HighWire\Clients\HWResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * AIRecommendations client class.
 *
 * @package  HighWire\Clients\AIRecommendations
 */
class AIRecommendations extends Client {

  /**
   * Limit the number of recommendations.
   */
  const LIMIT = 20;

  /**
   * The API KEY to pass to Accessinnovations API
   */
  const API_KEY = "b38cbe7a-e665-4659-b0df-a41376fa071a";

  /**
   * Create a new client object.
   *
   * @param \GuzzleHttp\Client $http_client
   *   A guzzle client object.
   */
  public function __construct(GuzzleClient $http_client) {
    parent::__construct($http_client);
  }

  /**
   * Get AI API recommendations for the content.
   *
   * @param string $content_id
   *   The content id.
   * @return \GuzzleHttp\Promise\Promise
   *   Returns a guzzle promise object that can be full filed by calling wait()
   */
  public function getRecommendationsAsync(string $content_id) {
    if (empty($content_id)) {
      throw new \Exception("Content ID is empty.", 1);
    }

    $request = $this->buildRequest('GET', "/api/recommend/" . $content_id . "/" . self::LIMIT . "/" . self::API_KEY);
    $http_promise = $this->sendAsync($request);

    $promise = new Promise(function () use (&$promise, $http_promise) {
      $resp = $http_promise->wait();
      $promise->resolve($resp->getBody());
    });

    return $promise;
  }

}
