<?php

namespace HighWire\Clients\ContentSync;

use GuzzleHttp\Promise\Promise;
use HighWire\Clients\HWResponse;
use HighWire\Clients\Client;

class ContentSync extends Client {

  /**
   * Get a content sync counter.
   */
  public function getCounterAsync(): Promise {

    $request = $this->buildRequest('GET', "/sequence/counter");
    $http_promise = $this->sendAsync($request);
    $promise = new Promise(function () use (&$promise, $http_promise) {
      $resp = $http_promise->wait();
      $resp_body = $resp->getBody();
      $hw_response = new HWResponse($resp, $resp_body);
      $promise->resolve($hw_response);
    });

    return $promise;
  }

}
