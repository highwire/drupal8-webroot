<?php

namespace HighWire\Clients\SiteData;

use HighWire\Clients\Client;
use GuzzleHttp\Promise\Promise;

/**
 * SiteData client.
 */
class SiteData extends Client {

  /**
   * Get the site data for one or more ids.
   *
   * @param array $ids
   *   The id (usually a corpus code) for which to look up site data.
   *
   * @return \GuzzleHttp\Promise\Promise
   *   Returns a guzzle promise object that can be fulfilled by calling wait().
   */
  public function getAsync(array $ids): Promise {
    if (count($ids) == 1) {
      $id = reset($ids);
      return $this->getSingleAsync($id);
    }

    return $this->getMultipleAsync($ids);
  }

  /**
   * Get the site data for a given set of ids.
   *
   * @param array $ids
   *   The ids (usually corpus codes) for which to look up site data.
   *
   * @return \GuzzleHttp\Promise\Promise
   *   Returns a guzzle promise object that can be fulfilled by calling wait().
   */
  public function getMultipleAsync(array $ids): Promise {
    $request = $this->buildRequest("GET", "lookup.json");
    $http_promise = $this->sendAsync($request);
    $promise = new Promise(function () use (&$promise, $http_promise, $ids) {
      $resp = $http_promise->wait();
      $data = json_decode((string) $resp->getBody(), TRUE);
      $filtered_data = [];
      foreach ($ids as $id) {
        if (!empty($data[$id])) {
          $filtered_data[$id] = $data[$id];
        }
      }
      $promise->resolve($filtered_data);
    });

    return $promise;
  }

  /**
   * Get the site data for a given id.
   *
   * @param string $id
   *   The id (usually a corpus code) for which to look up site data.
   *
   * @return \GuzzleHttp\Promise\Promise
   *   Returns a guzzle promise object that can be fulfilled by calling wait().
   */
  public function getSingleAsync(string $id): Promise {
    $request = $this->buildRequest("GET", "lookup/id/$id.json");
    $http_promise = $this->sendAsync($request);
    $promise = new Promise(function () use (&$promise, $http_promise) {
      $resp = $http_promise->wait();
      $data = json_decode((string) $resp->getBody(), TRUE);
      $promise->resolve($data);
    });

    return $promise;
  }

}