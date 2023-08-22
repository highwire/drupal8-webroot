<?php

namespace HighWire\Clients\Embargo;

use HighWire\Clients\HWResponse;
use HighWire\Clients\Client;
use GuzzleHttp\Promise\Promise;

/**
 * Embargo Client Class.
 */
class Embargo extends Client {

  /**
   * Get the embargo state for an array of apaths
   *
   * @param array $apaths
   *   An array of apaths.
   *
   * @return \GuzzleHttp\Promise\Promise
   *   Returns a guzzle promise object that can be full filed by calling wait()
   */
  public function getEmbargoStateAsync(array $apaths): Promise {
    $apaths = implode("\n", $apaths);
    $request = $this->buildRequest('POST', "apath", $apaths);
    $http_promise = $this->sendAsync($request);
    $promise = new Promise(function () use (&$promise, $http_promise) {
      $resp = $http_promise->wait();
      $results = json_decode($resp->getBody(), TRUE);

      if ($results['error'] === TRUE) {
        throw new \Exception($results['error_message']);
      }

      $hw_response = new HWResponse($resp, $results['results']);
      $promise->resolve($hw_response);
    });

    return $promise;
  }

}
