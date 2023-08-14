<?php

namespace HighWire\Clients\Date;

use HighWire\Parser\ExtractPolicy\ExtractPolicy;
use HighWire\Clients\HWResponse;
use HighWire\Clients\Client;
use GuzzleHttp\Promise\Promise;

/**
 * Date Svc Client Class.
 */
class Date extends Client {

  /**
   * Get a specific date from the date service.
   *
   * @param array $apaths
   *   An array of apaths.
   *
   * @return \GuzzleHttp\Promise\Promise
   *   Returns a guzzle promise object that can be full filed by calling wait()
   */
  public function getDatesAsync(array $apaths): Promise {
    $request = $this->buildRequest('POST', "get", implode("\n", $apaths), ['Content-type' => 'text/uri-list']);
    $http_promise = $this->sendAsync($request);
    $promise = new Promise(function () use (&$promise, $http_promise) {
        $resp = $http_promise->wait();
        $dates = json_decode(strval($resp->getBody()), TRUE);
        $hw_response = new HWResponse($resp, $dates);
        $promise->resolve($hw_response);
    });

    return $promise;
  }

}
