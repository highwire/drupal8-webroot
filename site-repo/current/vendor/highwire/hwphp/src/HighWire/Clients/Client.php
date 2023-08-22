<?php

namespace HighWire\Clients;

use GuzzleHttp\Pool;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\TransferStats;
use HighWire\Utility\TrustedIP;

/**
 * Client abstract class.
 */
abstract class Client {

  /**
   * All child classes should use the sendAsync method to execute requests.
   *
   * @var \GuzzleHttp\Client
   */
  protected $httpClient;

  /**
   * Defaults for all requests.
   *
   * @var array
   */
  private $defaultHeaders = [];

  /**
   * If set in sub class they will be added to every request.
   *
   * @var array
   */
  protected $headers = [];

  /**
   * The client config.
   *
   * @var array
   */
  protected $config = [];

  /**
   * Create a new client object.
   *
   * @param \GuzzleHttp\Client $http_client
   *   A guzzle client object.
   * @param array $config
   *   Any conifguration the client needs.
   */
  public function __construct(GuzzleClient $http_client, array $config = []) {
    $this->httpClient = $http_client;
    $this->config = $config;
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
    // Making use of magic methods here. When creating a new
    // client class, all you have to do is define the async request method.
    // Then if a user does not want to do an async request,
    // they can drop async from the method name and it'll just work.
    // Example: A4DExtract client class only has extractAsync()
    // method defined. A user can call extract()
    // and a non async request will be made
    // with out the method actually being defined.
    if (!method_exists($this, $name) && strtolower(substr($name, -5)) != 'async' && method_exists($this, $name . 'Async')) {
      $promise = call_user_func_array([$this, $name . 'Async'], $arguments);
      return $promise->wait();
    }
    elseif (!method_exists($this, $name)) {
      throw new \Exception("Method doesn't exist $name");
    }
  }

  /**
   * Create a GuzzleHttp\Psr7\Request.
   *
   * @param string $method
   *   HTTP method.
   * @param string|\Psr\Http\Message\UriInterface $uri
   *   URI.
   * @param string|null|resource|\Psr\Http\Message\StreamInterface $body
   *   Request body.
   * @param array $headers
   *   Request headers.
   * @param string $version
   *   Protocol version.
   *
   * @return \GuzzleHttp\Psr7\Request
   *   A guzzle request object that can be passed to guzzle client send method.
   */
  public function buildRequest($method, $uri, $body = NULL, array $headers = [], $version = '1.1') {
    $headers = array_merge($this->defaultHeaders, $this->headers, $headers);
    return new Request($method, $uri, $headers, $body, $version);
  }

  /**
   * Create a pool request.
   *
   * @param array|\Iterator $requests
   *   Requests or functions that return requests to send concurrently.
   * @param int $concurrency
   *   An integer specifying how many requests to execute concurrently.
   * @param array $additional_client_options
   *   An array of additional client options.
   *
   * @return \GuzzleHttp\Pool
   *   A guzzle pool object that can be used to make guzzle pool requests.
   */
  public function buildPoolRequest($requests, $concurrency = 5, array $additional_client_options = []) {
    return new Pool($this->httpClient, $requests, ['concurrency' => (int) $concurrency, 'options' => $additional_client_options]);
  }

  /**
   * Get the current guzzle configuration .
   *
   * @param string $option
   *   Optionaly pass a specific option.
   *
   * @return mixed
   *   Get a configuration option for a guzzle client.
   */
  public function getGuzzleConfig($option = NULL) {
    return $this->httpClient->getConfig($option);
  }

  /**
   * Do an async request to the service.
   *
   * @param \GuzzleHttp\Psr7\Request $request
   *   A fully formed psr7 request object.
   *
   * @see https://github.com/guzzle/psr7/blob/master/src/Request.php
   *
   * @param array $options
   *   Array of request options.
   *
   * @return \GuzzleHttp\Promise\Promise
   *   Returns a guzzle promise object that can be full filed by calling wait().
   */
  protected function sendAsync(Request $request, array $options = []) {
    return $this->httpClient->sendAsync($request, $options);
  }

  /**
   * Do a http request to the service.
   *
   * @param \GuzzleHttp\Psr7\Request $request
   *   A fully formed psr7 request object.
   * @param array $options
   *   Array of request options.
   *
   * @see https://github.com/guzzle/psr7/blob/master/src/Request.php
   *
   * @return \GuzzleHttp\Psr7\Response
   *   Returns a guzzle response object.
   */
  protected function send(Request $request, array $options = []) {
    return $this->httpClient->send($request, $options);
  }

  /**
   * Helper function for wrapping an exception message.
   *
   * @param string $message
   *   The exception message to throw.
   *
   * @throws \Exception
   */
  protected function throwException($message) {
    throw new \Exception(get_called_class() . ": " . $message);
  }

}
