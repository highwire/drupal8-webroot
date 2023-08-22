<?php

namespace HighWire\Clients\HTMLPDF;

use HighWire\Clients\HWResponse;
use HighWire\Clients\Client;
use GuzzleHttp\Promise\Promise;
use HighWire\Parser\HTMLPDF\ResponseItems;

/**
 * HTMLPDF Client Class.
 */
class HTMLPDF extends Client {

  /**
   * Get info about html to pdf binary.
   *
   * @param string $apath
   *   An apath.
   *
   * @return \GuzzleHttp\Promise\Promise
   *   Returns a guzzle promise object that can be full filed by calling wait()
   */
  public function getPDFInfoAsync($apath): Promise {
    return $this->getPDFInfoMultipleAsync([$apath]);
  }

  /**
   * Get info about a batch of html to pdf binaries.
   *
   * @param array $apaths
   *   The name of the extract policy, example - drupal-43.
   *
   * @return \GuzzleHttp\Promise\Promise
   *   Returns a guzzle promise object that can be full filed by calling wait()
   */
  public function getPDFInfoMultipleAsync(array $apaths): Promise {
    $uri = "list?";

    foreach ($apaths as $apath) {
      $uri .= 'src=' . $apath . '&';
    }

    $request = $this->buildRequest('GET', $uri);
    $http_promise = $this->sendAsync($request);
    $promise = new Promise(function () use (&$promise, $http_promise) {
      $resp = $http_promise->wait();
      $items = new ResponseItems($resp->getBody()->getContents());
      $hw_response = new HWResponse($resp, $items);
      $promise->resolve($hw_response);
    });

    return $promise;
  }

}
