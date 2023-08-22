<?php

namespace HighWire\Clients\CCT;

use GuzzleHttp\Client as GuzzleClient;
use HighWire\Clients\Client;
use GuzzleHttp\Promise\Promise;
use HighWire\Clients\HWResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * CCT client class.
 *
 * @package  HighWire\Clients\CCT
 */
class CCT extends Client {

  /**
   * The publisherID that's used to generate the request uri.
   *
   * @var string
   */
  protected $publisherId;

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
    $this->publisherId = $config['publisherId'];
  }

  /**
   * Get Collections for the publisher.
   *
   * @return \GuzzleHttp\Promise\Promise
   *   Returns a guzzle promise object that can be full filed by calling wait()
   */
  public function getCollectionsAsync(): Promise {

    $request = $this->buildRequest('GET', "/ccts/collection/" . $this->publisherId);
    $http_promise = $this->sendAsync($request);
    $promise = new Promise(function () use (&$promise, $http_promise) {
      $resp = $http_promise->wait();
      $resp_body = $resp->getBody();
      $collections_data = json_decode($resp_body, TRUE);
      $collections = new Collections($collections_data);
      $hw_response = new HWResponse($resp, $collections);
      $promise->resolve($hw_response);
    });

    return $promise;
  }

  /**
   * Get a landing page by id from the CCT Service.
   * 
   * @param string $landing_page_id
   * @return Promise
   *   Returns a guzzle promise object that can be full filed by calling wait()
   */
  public function getLandingPageAsync(string $landing_page_id): Promise {

    if (empty($landing_page_id)) {
      throw new \Exception("Landing Page ID is empty.", 1);
    }

    $query = "?landingPageId=" . $landing_page_id;

    $request = $this->buildRequest('GET', "/ccts/collection/" . $this->publisherId . $query);
    $http_promise = $this->sendAsync($request);
    $promise = new Promise(function () use (&$promise, $http_promise) {
      $resp = $http_promise->wait();
      $resp_body = $resp->getBody();
      $collection_data = json_decode($resp_body, TRUE);
      if (!empty($collection_data)) {
        $collection = new Collection($collection_data[0]);
      }
      else {
        $collection = [];
      }
      $hw_response = new HWResponse($resp, $collection);
      $promise->resolve($hw_response);
    });

    return $promise;
  }

  /**
   * Get a Collection Members by Collection id from the CCT Service.
   * 
   * @param int $collection_id
   *   A CCT Collection Id.
   * @return Promise
   *   Returns a guzzle promise object that can be full filed by calling wait()
   */
  public function getMembersAsync(int $collection_id, int $num_results = 10, int $offset = 1): Promise {

    if (empty($collection_id)) {
      throw new \Exception("Collection ID is empty.", 1);
    }

    $query = "?numResults=" . $num_results . "&firstResult=" . $offset;

    $request = $this->buildRequest('GET', "/ccts/collection/members/" . $collection_id . $query);
    $http_promise = $this->sendAsync($request);
    $promise = new Promise(function () use (&$promise, $http_promise) {
      $resp = $http_promise->wait();
      $resp_body = $resp->getBody();
      $members_data = json_decode($resp_body, TRUE);
      $members = new Members($members_data);
      $hw_response = new HWResponse($resp, $members);
      $promise->resolve($hw_response);
    });

    return $promise;
  }

  /**
   * Proxy a the featured image for a collection from the CCT service.
   * 
   * @param int $image_id
   *   A CCT Collection featured image id.
   */
  public function proxyFeaturedImageAsync(int $image_id) {
    $headers = [];

    $request = $this->buildRequest('GET', "/ccts/collection/download/image/" . $image_id);
    $resp = $this->send($request);
    $response = new StreamedResponse(NULL, $resp->getStatusCode(), $headers);
    $response->setCallback(function () use ($resp) {
      $body = $resp->getBody();
      while (!$body->eof()) {
        print($body->read(1024));
        flush();
      }
      flush();
    });

    $response->send();
    exit(0);

  }

  /**
   * Prepare a request to the CCT service.
   *
   * @param string $image_id
   *   The cct image ID as a string.
   * @param \Psr\Http\Message\RequestInterface $client_request
   *   The request from the end client.
   * @param bool $edge_cache
   *   Allow this resource to be cached at the edge (eg in CloudFlare).
   *   This will pass the ETag header back to sass, and tag on "Cache-Control: Public, s-maxage=0".
   *   This means the resource can be cached on the edge, but should revalidated everytime.
   *
   * @return GuzzleRequest;
   *   A prepared request.
   */
  protected function prepareRequest($image_id, RequestInterface $client_request = NULL, $edge_cache = FALSE) {
    // Create a default client if not provided.
    if (empty($client_request)) {
      // We create it as a symfony client, then cast it as a psr-7 client.
      $symfony_request = Request::create('http://localhost');
      $psr7_factory = new DiactorosFactory();
      $client_request = $psr7_factory->createRequest($symfony_request);
    }

    $method = $client_request->getMethod();
    if ($method != 'GET' && $method != 'HEAD') {
      throw new \Exception('CCT Service: Proxy method not allowed: ' . $method);
    }

    // Build a list of passed header values.
    $headers = [];
    foreach ($this->passedHeaders as $header) {
      if ($client_request->hasHeader($header)) {
        $headers[$header] = $client_request->getHeader($header);
      }
    }

    // Pass ETag if we are caching on the edge (CloudFlare)
    if ($edge_cache && $client_request->hasHeader('ETag')) {
      $headers['ETag'] = $client_request->getHeader('ETag');
    }

    // Create the request to the binary service.
    $request = $this->buildRequest($method, "entity/" . $image_id, NULL, $headers);

    return $request;
  }

}
