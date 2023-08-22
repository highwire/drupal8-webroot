<?php

namespace HighWire\Clients\AtomCollections;

use HighWire\Clients\Client;
use GuzzleHttp\Promise\Promise;
use HighWire\Parser\AtomCollections\Membership;
use HighWire\Parser\AtomCollections\TermMembership;

/**
 * Atom Collections Client.
 */
class AtomCollections extends Client {

  /**
   * Always ask for the XML respons from the atom-collections service.
   *
   * {@inheritdoc}
   */
  protected $headers = ['Accept' => 'application/xml'];

  /**
   * Get category membership for an apath.
   *
   * @param string $apath
   *   The apath to get the categories for.
   * @param string $publiser_code
   *   The publisher code.
   * @param string $workspace
   *   Workspace. Default to "content".
   *
   * @return \GuzzleHttp\Promise\Promise
   *   A guzzle response object.
   */
  public function getMembershipAsync(string $apath, string $publiser_code, string $workspace = 'content'): Promise {
    $uri = "$publiser_code/$workspace/atom?" . http_build_query(['member' => $apath]);
    $request = $this->buildRequest('GET', $uri);
    $http_promise = $this->sendAsync($request);

    $promise = new Promise(function () use (&$promise, $http_promise) {
      $resp = $http_promise->wait();
      $membership = new Membership($resp->getBody());
      $membership->setResponse($resp);
      $promise->resolve($membership);
    });

    return $promise;
  }

  /**
   * Get all collection members for a given collection.
   *
   * @param string $collection_id
   *   The collection id to get the members for.
   * @param string $publiser_code
   *   The publisher code.
   * @param string $scheme
   *   The scheme. Default to "subject".
   * @param string $workspace
   *   Workspace. Default to "content".
   * @param int $max_results
   *   The total number of max results to return. Note if
   *   you increase this number the service can take multiple minutes to respond.
   *
   * @return \GuzzleHttp\Promise\Promise
   *   A guzzle response object.
   */
  public function getTermMembershipAsync(string $collection_id, string $publiser_code, string $scheme = 'subject', string $workspace = 'content', int $max_results = 1000): Promise {
    $uri = "$publiser_code/$workspace/atom?" . http_build_query(['term' => $collection_id, 'scheme' => $scheme, 'max-results' => $max_results]);
    $request = $this->buildRequest('GET', $uri);
    $http_promise = $this->sendAsync($request);

    $promise = new Promise(function () use (&$promise, $http_promise) {
      $resp = $http_promise->wait();
      $membership = new TermMembership($resp->getBody());
      $membership->setResponse($resp);
      $promise->resolve($membership);
    });

    return $promise;
  }

}
