<?php

namespace HighWire\Clients\AccessControl;

use HighWire\Parser\AC\Request;
use HighWire\Clients\Client;
use HighWire\Parser\AC\Response;
use GuzzleHttp\Promise\Promise;
use GuzzleHttp\Client as GuzzleClient;

/**
 * Access Control Client.
 */
class AccessControl extends Client {

  protected $headers = [
    'Content-Type' => 'application/vnd.hw.ac-runtime-request+xml',
  ];

  /**
   * The publisherID that's used to generate the request uri.
   * Ie. /runtime/[publisherId]
   *
   * @var string
   */
  protected $publisherId;

  /**
   * The request context.
   * Ie. /runtime/[publisherId]?context=[context]
   *
   * @var string
   */
  protected $context;

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
    $this->context = $config['context'];
    $this->publisherId = $config['publisherId'];
  }

  /**
   * Make an asynchronous request to the access control service.
   *
   * @param \HighWire\Parser\AC\Request $request
   *   The ac request to send.
   *
   * @return \GuzzleHttp\Promise\Promise
   *   Returns a guzzle promise object that can be full filed by calling wait()
   */
  public function accessControlAsync(Request $request): Promise {
    $uri = 'runtime/' . $this->publisherId;
    $uri .= '?' . http_build_query(['context' => $this->context]);
    $psr7_request = $this->buildRequest('post', $uri, $request);
    $http_promise = $this->sendAsync($psr7_request);

    $promise = new Promise(function () use (&$promise, $http_promise) {
      $resp = $http_promise->wait();
      $ac_response = new Response(strval($resp->getBody()));
      $ac_response->setResponse($resp);
      $promise->resolve($ac_response);
    });

    return $promise;
  }

}
