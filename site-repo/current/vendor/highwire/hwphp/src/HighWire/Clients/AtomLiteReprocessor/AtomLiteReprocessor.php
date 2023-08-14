<?php

namespace HighWire\Clients\AtomLiteReprocessor;

use HighWire\Clients\Client;
use HighWire\PayloadFetcherInterface;
use HighWire\ExtractPolicyServiceInterface;
use HighWire\Clients\HWResponse;
use GuzzleHttp\Promise\Promise;

/**
 * AtomLiteReprocessor client is used to connect to the AtomLiteReprocessor service.
 */
class AtomLiteReprocessor extends Client {

  /**
   * Index a given apath in atomlite
   *
   * @return \GuzzleHttp\Promise\Promise
   *   Returns a guzzle promise object that can be full filed by calling wait()
   */
  public function indexApathAsync(string $apath): Promise {
    $request = $this->buildRequest('POST', "reprocess" . $apath);
    $http_promise = $this->sendAsync($request);
    $promise = new Promise(function () use (&$promise, $http_promise) {
      $resp = $http_promise->wait();
      $hw_response = new HWResponse($resp, []);
      $promise->resolve($hw_response);
    });

    return $promise;
  }

}
