<?php

namespace HighWire\Clients\Atom;

use HighWire\Clients\HWResponse;
use HighWire\Clients\Client;
use GuzzleHttp\Promise\Promise;

/**
 * Atom client for getting records from the atom store.
 *
 * @note DO NOT USE THIS CLASS FOR RUNTIME DATA.
 *
 * @method \HighWire\Clients\HWResponse getResource(string $apath)
 * @method \HighWire\Clients\HWResponse headResource(string $apath)
 */
class Atom extends Client {

  /**
   * Pass these headers from the client to the atom service.
   *
   * @var array
   */
  protected $passedHeaders = [
    'If-None-Match',
    'If-Match',
    'If-Modified-Since',
    'If-Unmodified-Since',
    'If-Range',
    'Range',
  ];

  /**
   * Get a resource.
   *
   * @param string $apath
   *   The apath to the resource.
   * 
   * @param array $params
   *   Additional params to pass to the request.
   * 
   *   Query Form Parameters:
   *    Parameter                Default Value  Alternative Values
   *    ------------------------ -------------- ------------------
   *    query-form               'expand'       'search'
   *
   *    Scoping Parameters:
   *    Parameter                Default Value  Alternative Values
   *    ------------------------ -------------- ------------------
   *    with-ancestors           FALSE          TRUE | number
   *    with-descendants         FALSE          TRUE | number
   *    with-descendants-lt      0              number
   *    ancestors-role                          role-uri
   *    descendants-role                        role-uri
   *
   *    Expansion Parameters:
   *    Parameter                Default Value  Alternative Values
   *    ------------------------ -------------- -------------------
   *    with-variant             FALSE          TRUE | 1
   *    with-ancestors-variant   FALSE          TRUE | number
   *    with-descendants-variant FALSE          TRUE | number
   *    variant-role                            role-uri
   *    ancestors-variant-role                  role-uri
   *    descendants-variant-role                role-uri
   *    variant-lang                            language-tag
   *    ancestors-variant-lang                  language-tag
   *    descendants-variant-lang                language-tag
   *    content                  'alternate'    'inline' | 'out-of-line'
   *    ancestors-content        'alternate'    'inline' | 'out-of-line'
   *    descendants-content      'alternate'    'inline' | 'out-of-line'
   *    form                     'entry'        'feed'
   *
   *    Paging Parameters:
   *    Parameter                Default Value  Alternative Values
   *    ------------------------ -------------- ------------------
   *    max-results                             number
   *    start-index              1              number
   *    .
   *
   * @return \GuzzleHttp\Promise\Promise
   *   Guzzle promise.
   */
  public function getResourceAsync(string $apath, array $params = []): Promise {
    // transform TRUE into "yes" or FALSE into "no"
    array_walk($params, function(&$value) {
      if ($value === TRUE) {
        $value = 'yes';
      }
      if ($value === FALSE) {
        $value = 'no';
      }
    });

    $request = $this->buildRequest('GET', $apath . '?' . http_build_query($params));
    $http_promise = $this->sendAsync($request);
    $promise = new Promise(function () use (&$promise, $http_promise) {
      $resp = $http_promise->wait();
      $hw_response = new HWResponse($resp, $resp->getBody());
      $promise->resolve($hw_response);
    });

    return $promise;
  }

  /**
   * HEAD a resource.
   *
   * @param string $apath
   *   The apath to the resource.
   *
   * @return \GuzzleHttp\Promise\Promise
   *   Guzzle promise.
   */
  public function headResourceAsync(string $apath): Promise {
    $request = $this->buildRequest('HEAD', $apath);
    $http_promise = $this->sendAsync($request);
    $promise = new Promise(function () use (&$promise, $http_promise) {
      $resp = $http_promise->wait();
      $data = [];
      foreach ($resp->getHeaders() as $name => $values) {
        $data[$name] = $values[0];
      }
      $hw_response = new HWResponse($resp, $data);
      $promise->resolve($hw_response);
    });

    return $promise;
  }

  /**
   * Query the atom store given a regex pattern.
   *
   * @param string $pattern
   *   A regex pattern to run against paths in the atom store.
   *
   * @return array
   *   An array of paths found.
   */
  public function pathsFromPattern($pattern) {
    $forest_resp = $this->getAtomForests();
    $forests = $forest_resp->getData();
    $paths = [];

    if (!empty($forests)) {
      foreach ($forests as $forest) {
        $request = $this->buildRequest('GET', "/svc.atom?query-form=search&canned-query=/hwc/list-extant-resources.xqy&type=pattern&pattern=$pattern&forest=$forest");
        $response = $this->send($request);
        $response_body = trim(strip_tags($response->getBody()));
        $data = !empty($response_body) ? explode("\n", $response_body) : [];
        if (!empty($data)) {
          $paths = array_merge($paths, $data);
        }
      }
    }

    return $paths;
  }

  /**
   * Get all marklogic forests.
   *
   * @return \GuzzleHttp\Promise\Promise
   *   Guzzle promise.
   */
  public function getAtomForestsAsync() {
    $request = $this->buildRequest('GET', '/svc.atom?query-form=search&canned-query=/hwc/list-resources.xqy&type=forests');
    $http_promise = $this->sendAsync($request);
    $promise = new Promise(function () use (&$promise, $http_promise) {
      $resp = $http_promise->wait();
      $data = explode("\n", trim(strip_tags($resp->getBody())));
      $hw_response = new HWResponse($resp, $data);
      $promise->resolve($hw_response);
    });

    return $promise;
  }

}
