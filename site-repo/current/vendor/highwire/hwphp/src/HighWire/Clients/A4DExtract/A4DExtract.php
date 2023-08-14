<?php

namespace HighWire\Clients\A4DExtract;

use HighWire\Parser\ExtractPolicy\ExtractPolicy;
use HighWire\Clients\HWResponse;
use HighWire\Clients\Client;
use GuzzleHttp\Promise\Promise;

/**
 * Extract Client Class.
 */
class A4DExtract extends Client {

  /**
   * Get an extract policy definition.
   *
   * @param string $policy_name
   *   The name of the extract policy, example - drupal-43.
   *
   * @return \GuzzleHttp\Promise\Promise
   *   Returns a guzzle promise object that can be full filed by calling wait()
   */
  public function getPolicyAsync($policy_name): Promise {
    $request = $this->buildRequest('GET', "policy/$policy_name/definition");
    $http_promise = $this->sendAsync($request);
    $promise = new Promise(function () use (&$promise, $http_promise) {
        $resp = $http_promise->wait();
        $policy = new ExtractPolicy(strval($resp->getBody()));
        $hw_response = new HWResponse($resp, $policy);
        $promise->resolve($hw_response);
    });

    return $promise;
  }

  /**
   * Get an extract for a given policy and and id.
   *
   * @param string $policy_name
   *   The name of the extract policy, example - drupal-43.
   * @param string $id
   *   The id of the resource to execute the extract policy against.
   *
   * @return \GuzzleHttp\Promise\Promise
   *   Returns a guzzle promise object that can be full filed by calling wait()
   */
  public function extractAsync($policy_name, $id): Promise {
    $request = $this->buildRequest('GET', "policy/$policy_name/extract/$id");
    $http_promise = $this->sendAsync($request);
    $promise = new Promise(function () use (&$promise, $http_promise) {
      $resp = $http_promise->wait();
      $hw_response = new HWResponse($resp, json_decode((string) $resp->getBody(), TRUE));
      $promise->resolve($hw_response);
    });

    return $promise;
  }

}
