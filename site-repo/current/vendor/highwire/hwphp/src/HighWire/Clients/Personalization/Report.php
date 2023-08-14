<?php

namespace HighWire\Clients\Personalization;

use GuzzleHttp\Client as GuzzleClient;
use HighWire\Clients\Client;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Psr\Http\Message\RequestInterface;

/**
 * Class Report
 *
 * @package HighWire\Clients\Personalization
 */
class Report extends Client {

  /**
   * The personalization context.
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
  }

  /**
   * Get the personalization report from the service.
   *
   * @param \Psr\Http\Message\RequestInterface|null $client_request
   *   Client request.
   * @param array $additional_headers
   *   Additional headers.
   * @param string $context
   *   Personalization context.
   * @param string $type
   *   The type of report to fetch.
   */
  public function getReport(RequestInterface $client_request = NULL, array $additional_headers = [], string $context, string $type) {

    $headers = [];
    $method = $client_request->getMethod();
    $uri = $client_request->getUri();
    $uri .= "/data/" . $context . "/" . $type;
    $headers = array_merge($headers, $additional_headers);
    $request = $this->buildRequest($method, $uri, $client_request->getBody(), $headers);
    $resp = $this->send($request);
    return $resp->getBody()->getContents();
  }

}
