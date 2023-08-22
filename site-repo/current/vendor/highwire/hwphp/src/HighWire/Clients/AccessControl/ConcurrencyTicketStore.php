<?php

namespace HighWire\Clients\AccessControl;

use HighWire\Parser\AC\Request;
use HighWire\Clients\Client;
use HighWire\Parser\AC\Response;
use GuzzleHttp\Promise\Promise;
use GuzzleHttp\Client as GuzzleClient;
use HighWire\Clients\HWResponse;
use Drupal\Component\Serialization\Json;
use Drupal\Core\Config\ConfigFactory;

/**
 * Concurrency Ticket Store Client.
 */
class ConcurrencyTicketStore extends Client {

  /**
   * Drupal config factory.
   *
   * @var \Drupal\Core\Config\ConfigFactory
   */
  protected $configFactory;

  /**
   * The sigma site Id.
   * @var String
   */
  private $siteId;

  /**
   * The sigma site secret.
   * @var String
   */
  private $siteSecret;

  /**
   * Create a new client object.
   *
   * @param \GuzzleHttp\Client $http_client
   *   A guzzle client object.
   * @param array $config
   *   Any configuration the client needs.
   */
  public function __construct(GuzzleClient $http_client, array $config = [])
  {
    parent::__construct($http_client, $config);

    $this->configFactory = \Drupal::service('config.factory');
    $this->sigmaConfig = $this->configFactory->get('openid_connect.settings.sams-sigma');
    $sigma_settings = $this->sigmaConfig->get('settings');
    $this->siteId = $sigma_settings['client_id'];
    $this->siteSecret = $sigma_settings['client_secret'];
  }

  /**
   * Make an asynchronous request to the concurrency ticket store service.
   *
   * @param string $session_hash
   *   Unique client session ID of a client requesting a resource - this should NOT be the user's web session id, but
   *   rather computed from that ID in the content site's code using some hashing technique as a security measure.
   *
   * @param string $license_id
   *   The license id to set the concurrency ticket for.
   *
   * @param string $allowed_concurrency_count
   *   The concurrency seat limit for the license.
   *
   * @return \GuzzleHttp\Promise\Promise
   *   Returns a guzzle promise object that can be full filed by calling wait()
   */
  public function createConcurrencyTicketAsync($session_hash, $license_id, $allowed_concurrency_count): Promise {
    $params = [
      'sessionHash' => $session_hash,
      'siteId' => $this->siteId,
      'licenseId' => $license_id,
      'allowedConcurrencyCount' => $allowed_concurrency_count
    ];
    $uri = '/api/concurrency';
    $uri .= '?' . http_build_query($params);

    $request = $this->buildRequest('POST', $uri, NULL, ['Authorization' => 'Basic ' . $this->getAuth()]);
    $http_promise = $this->sendAsync($request);
    $promise = new Promise(function () use (&$promise, $http_promise) {
      $request = $http_promise->wait();
      $response = $request->getBody();
      $raw_response = $response->getContents();

      $hw_response = new HWResponse($request, $raw_response);
      $promise->resolve($hw_response);
    });

    return $promise;

  }

  /**
   * Make an asynchronous request to the get the concurrency ticket count.
   *
   * @param string $license_id
   *   The license id to set the concurrency ticket for.
   *
   * @return \GuzzleHttp\Promise\Promise
   *   Returns a guzzle promise object that can be full filed by calling wait()
   */
  public function getConcurrencyLicenseUsageAsync($license_id): Promise {
    $uri = '/api/concurrency/' . $license_id;

    $request = $this->buildRequest('GET', $uri, NULL, ['Authorization' => 'Basic ' . $this->getAuth()]);
    $http_promise = $this->sendAsync($request);
    $promise = new Promise(function () use (&$promise, $http_promise) {
      $request = $http_promise->wait();
      $response = $request->getBody();
      $raw_response = JSON::decode($response->getContents());

      $hw_response = new HWResponse($request, $raw_response);
      $promise->resolve($hw_response);
    });

    return $promise;

  }

  /**
   * Make an asynchronous request to delete a concurrency ticket.
   *
   * @param string $session_hash
   *   Unique client session ID of a client requesting a resource - this should NOT be the user's web session id, but
   *   rather computed from that ID in the content site's code using some hashing technique as a security measure.
   *
   * @return \GuzzleHttp\Promise\Promise
   *   Returns a guzzle promise object that can be full filed by calling wait()
   */
  public function deleteConcurrencyTicketAsync($session_hash): Promise
  {

    $uri = '/api/concurrency/' . $session_hash . '/' . $this->siteId;

    $request = $this->buildRequest('DELETE', $uri, NULL, ['Authorization' => 'Basic ' . $this->getAuth()]);
    $http_promise = $this->sendAsync($request);
    $promise = new Promise(function () use (&$promise, $http_promise) {
      $request = $http_promise->wait();
      $response = $request->getBody();
      $raw_response = $response->getContents();

      $hw_response = new HWResponse($request, $raw_response);
      $promise->resolve($hw_response);
    });

    return $promise;

  }

  /**
   * Get the site authentication for concurrency service.
   *
   * @return string
   *   Base 64 encoded auth string.
   */
  protected function getAuth() {
    return base64_encode($this->siteId . ':' . $this->siteSecret);
  }

}
